package com.barataribeiro.medicore.config.security;

import com.barataribeiro.medicore.config.ApplicationAuditAware;
import lombok.RequiredArgsConstructor;
import nz.net.ultraq.thymeleaf.layoutdialect.LayoutDialect;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.data.domain.AuditorAware;
import org.springframework.data.redis.connection.RedisConnectionFactory;
import org.springframework.data.redis.core.RedisOperations;
import org.springframework.data.redis.core.RedisTemplate;
import org.springframework.data.redis.serializer.RedisSerializer;
import org.springframework.session.MapSession;
import org.springframework.session.config.SessionRepositoryCustomizer;
import org.springframework.session.data.redis.RedisIndexedSessionRepository;
import org.springframework.session.data.redis.RedisSessionExpirationStore;
import org.springframework.session.data.redis.RedisSessionMapper;
import org.springframework.session.data.redis.SortedSetRedisSessionExpirationStore;
import org.springframework.web.cors.CorsConfiguration;
import org.springframework.web.cors.UrlBasedCorsConfigurationSource;
import org.springframework.web.filter.CorsFilter;

import java.util.Arrays;
import java.util.Collections;
import java.util.Map;
import java.util.function.BiFunction;

import static org.springframework.http.HttpHeaders.*;

@Configuration
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class BeansConfig {
    private static final long MAX_AGE_SECS = 3600;

    @Value("${api.security.cors.origins}")
    private String allowedOrigins;

    @Bean
    public AuditorAware<String> auditorAware() {
        return new ApplicationAuditAware();
    }

    @Bean
    public LayoutDialect layoutDialect() {
        return new LayoutDialect();
    }

    @Bean
    public CorsFilter corsFilter() {
        final UrlBasedCorsConfigurationSource source = new UrlBasedCorsConfigurationSource();
        final CorsConfiguration config = new CorsConfiguration();

        config.setAllowCredentials(true);
        config.setAllowedOrigins(Collections.singletonList(allowedOrigins));
        config.setAllowedHeaders(Arrays.asList(ORIGIN, CONTENT_TYPE, ACCEPT, AUTHORIZATION));
        config.setAllowedMethods(Arrays.asList("GET", "POST", "PUT", "PATCH", "DELETE", "OPTIONS"));
        config.setMaxAge(MAX_AGE_SECS);
        source.registerCorsConfiguration("/**", config);

        return new CorsFilter(source);
    }

    @Bean
    SessionRepositoryCustomizer<RedisIndexedSessionRepository> redisSessionRepositoryCustomizer() {
        return redisSessionRepository -> redisSessionRepository
                .setRedisSessionMapper(new SafeRedisSessionMapper(redisSessionRepository.getSessionRedisOperations()));
    }

    @Bean
    public RedisSessionExpirationStore redisSessionExpirationStore(RedisConnectionFactory redisConnectionFactory) {
        RedisTemplate<String, Object> redisTemplate = new RedisTemplate<>();
        redisTemplate.setKeySerializer(RedisSerializer.string());
        redisTemplate.setHashKeySerializer(RedisSerializer.string());
        redisTemplate.setConnectionFactory(redisConnectionFactory);
        redisTemplate.afterPropertiesSet();
        return new SortedSetRedisSessionExpirationStore(redisTemplate, RedisIndexedSessionRepository.DEFAULT_NAMESPACE);
    }

    static class SafeRedisSessionMapper implements BiFunction<String, Map<String, Object>, MapSession> {
        private final RedisSessionMapper delegate = new RedisSessionMapper();
        private final RedisOperations<String, Object> redisOperations;

        SafeRedisSessionMapper(RedisOperations<String, Object> redisOperations) {
            this.redisOperations = redisOperations;
        }

        @Override
        public MapSession apply(String sessionId, Map<String, Object> map) {
            try {
                return this.delegate.apply(sessionId, map);
            } catch (IllegalStateException ex) {
                this.redisOperations.delete("spring:session:sessions:" + sessionId);
                return null;
            }
        }

    }
}
