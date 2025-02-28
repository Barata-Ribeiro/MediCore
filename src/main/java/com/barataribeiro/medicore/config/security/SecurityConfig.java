package com.barataribeiro.medicore.config.security;

import com.barataribeiro.medicore.utils.ApplicationConstants;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.config.annotation.method.configuration.EnableMethodSecurity;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.annotation.web.configuration.EnableWebSecurity;
import org.springframework.security.config.annotation.web.configurers.HeadersConfigurer;
import org.springframework.security.config.annotation.web.configurers.RequestCacheConfigurer;
import org.springframework.security.config.annotation.web.configurers.SessionManagementConfigurer;
import org.springframework.security.config.http.SessionCreationPolicy;
import org.springframework.security.crypto.argon2.Argon2PasswordEncoder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.security.web.SecurityFilterChain;
import org.springframework.security.web.authentication.rememberme.RememberMeAuthenticationFilter;
import org.springframework.security.web.csrf.CookieCsrfTokenRepository;
import org.springframework.security.web.header.writers.XXssProtectionHeaderWriter;
import org.springframework.session.data.redis.config.annotation.web.http.EnableRedisIndexedHttpSession;

import java.time.Duration;

import static org.springframework.security.config.Customizer.withDefaults;

@Configuration
@EnableWebSecurity
@EnableRedisIndexedHttpSession
@EnableMethodSecurity(securedEnabled = true)
public class SecurityConfig {
    @Value("${api.security.rememberMeKey}")
    private String rememberMeKey;

    @Value("${api.security.argon2.salt}")
    private int salt;
    @Value("${api.security.argon2.length}")
    private int length;
    @Value("${api.security.argon2.parallelism}")
    private int parallelism;
    @Value("${api.security.argon2.memory}")
    private int memory;
    @Value("${api.security.argon2.iterations}")
    private int iterations;

    @Bean
    public SecurityFilterChain securityFilterChain(HttpSecurity http,
                                                   LoginSuccessHandler successHandler,
                                                   SessionMetadataFilter sessionMetadataFilter) throws Exception {
        http.cors(withDefaults())
            .csrf(csrf -> csrf
                    .csrfTokenRepository(CookieCsrfTokenRepository.withHttpOnlyFalse())
                    .csrfTokenRequestHandler(new SpaCsrfTokenRequestHandler())
            )
            .headers(headers -> headers
                    .httpStrictTransportSecurity(withDefaults())
                    .xssProtection(xXssConfig -> xXssConfig
                            .headerValue(XXssProtectionHeaderWriter.HeaderValue.ENABLED_MODE_BLOCK))
                    .frameOptions(HeadersConfigurer.FrameOptionsConfig::sameOrigin)
                    .permissionsPolicyHeader(policy -> policy.policy("geolocation=(), microphone=(), camera=()")))
            .requestCache(RequestCacheConfigurer::disable)
            .sessionManagement(session -> session.sessionCreationPolicy(SessionCreationPolicy.IF_REQUIRED)
                                                 .invalidSessionUrl("/login?invalid_session")
                                                 .sessionFixation(SessionManagementConfigurer
                                                                          .SessionFixationConfigurer::changeSessionId)
                                                 .maximumSessions(1)
                                                 .maxSessionsPreventsLogin(true)
                                                 .expiredUrl("/login?expired"))
            .authorizeHttpRequests(authorize -> authorize
                    .requestMatchers(ApplicationConstants.getAuthWhitelist()).permitAll()
                    .anyRequest().authenticated())
            .formLogin(form -> form
                    .loginPage("/login")
                    .usernameParameter("username")
                    .passwordParameter("password")
                    .successHandler(successHandler)
                    .failureUrl("/login?failed"))
            .rememberMe(rememberMe -> rememberMe
                    .rememberMeParameter("rememberMe")
                    .useSecureCookie(true)
                    .tokenValiditySeconds((int) Duration.ofDays(180).getSeconds())
                    .key(rememberMeKey))
            .logout(logout -> logout.logoutSuccessUrl("/")
                                    .invalidateHttpSession(true)
                                    .clearAuthentication(true)
                                    .deleteCookies("SESSION", "remember-me", "XSRF-TOKEN"))
            .addFilterAfter(sessionMetadataFilter, RememberMeAuthenticationFilter.class);

        return http.build();
    }

    @Bean
    public PasswordEncoder passwordEncoder() {
        return new Argon2PasswordEncoder(salt, length, parallelism, memory, iterations);
    }
}