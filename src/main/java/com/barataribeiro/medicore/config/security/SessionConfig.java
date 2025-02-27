package com.barataribeiro.medicore.config.security;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonTypeInfo;
import com.fasterxml.jackson.databind.ObjectMapper;
import com.fasterxml.jackson.databind.SerializationFeature;
import com.fasterxml.jackson.datatype.hibernate6.Hibernate6Module;
import lombok.RequiredArgsConstructor;
import org.jetbrains.annotations.NotNull;
import org.springframework.beans.factory.BeanClassLoaderAware;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.data.redis.connection.RedisConnectionFactory;
import org.springframework.data.redis.core.RedisOperations;
import org.springframework.data.redis.core.RedisTemplate;
import org.springframework.data.redis.serializer.GenericJackson2JsonRedisSerializer;
import org.springframework.data.redis.serializer.RedisSerializer;
import org.springframework.security.jackson2.CoreJackson2Module;
import org.springframework.security.jackson2.SecurityJackson2Modules;
import org.springframework.session.MapSession;
import org.springframework.session.config.SessionRepositoryCustomizer;
import org.springframework.session.data.redis.RedisIndexedSessionRepository;
import org.springframework.session.data.redis.RedisSessionExpirationStore;
import org.springframework.session.data.redis.RedisSessionMapper;
import org.springframework.session.data.redis.SortedSetRedisSessionExpirationStore;

import java.util.*;
import java.util.function.BiFunction;

@Configuration
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class SessionConfig implements BeanClassLoaderAware {
    private ClassLoader loader;

    /**
     * Note that the bean name for this bean is intentionally
     * {@code springSessionDefaultRedisSerializer}. It must be named this way to override
     * the default {@link RedisSerializer} used by Spring Session.
     */
    @Bean
    public RedisSerializer<Object> springSessionDefaultRedisSerializer() {
        return new GenericJackson2JsonRedisSerializer(objectMapper());
    }

    /**
     * Customized {@link ObjectMapper} to add mix-in for class that doesn't have default
     * constructors
     *
     * @return the {@link ObjectMapper} to use
     */
    /**
     * Customized {@link ObjectMapper} to add mix-in for class that doesn't have default
     * constructors
     *
     * @return the {@link ObjectMapper} to use
     */
    private @NotNull ObjectMapper objectMapper() {
        ObjectMapper mapper = new ObjectMapper();
        mapper.registerModule(new CoreJackson2Module());

        List<com.fasterxml.jackson.databind.Module> modules = SecurityJackson2Modules.getModules(this.loader);
        mapper.activateDefaultTyping(mapper.getPolymorphicTypeValidator(), ObjectMapper.DefaultTyping.NON_FINAL,
                                     JsonTypeInfo.As.PROPERTY);

        mapper.registerModules(modules);
        mapper.registerModule(new Hibernate6Module());
        mapper.addMixIn(Collections.synchronizedSet(new HashSet<>()).getClass(), SynchronizedSetMixin.class);
        mapper.addMixIn(Collections.synchronizedSet(new LinkedHashSet<>()).getClass(), SynchronizedSetMixin.class);
        mapper.addMixIn(com.barataribeiro.medicore.features.user.AppUser.class, AppUserMixin.class);
        mapper.enable(SerializationFeature.FAIL_ON_EMPTY_BEANS);
        mapper.disable(SerializationFeature.WRITE_DATES_AS_TIMESTAMPS);
        return mapper;
    }

    /*
     * @see
     * org.springframework.beans.factory.BeanClassLoaderAware#setBeanClassLoader(java.lang
     * .ClassLoader)
     */
    @Override
    public void setBeanClassLoader(@NotNull ClassLoader classLoader) {
        this.loader = classLoader;
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

    @JsonTypeInfo(use = JsonTypeInfo.Id.CLASS, include = JsonTypeInfo.As.PROPERTY, property = "@class")
    public abstract static class SynchronizedSetMixin {}

    @JsonIgnoreProperties({
            "enabled",
            "accountNonExpired",
            "accountNonLocked",
            "credentialsNonExpired",
            "authorities"
    })
    @JsonTypeInfo(use = JsonTypeInfo.Id.CLASS, include = JsonTypeInfo.As.PROPERTY, property = "@class")
    public abstract static class AppUserMixin {}

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
