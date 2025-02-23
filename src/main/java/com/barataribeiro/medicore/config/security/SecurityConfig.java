package com.barataribeiro.medicore.config.security;

import com.barataribeiro.medicore.utils.ApplicationConstants;
import lombok.RequiredArgsConstructor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.config.annotation.method.configuration.EnableMethodSecurity;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.annotation.web.configuration.EnableWebSecurity;
import org.springframework.security.config.annotation.web.configurers.HeadersConfigurer;
import org.springframework.security.crypto.argon2.Argon2PasswordEncoder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.security.web.SecurityFilterChain;
import org.springframework.security.web.header.writers.XXssProtectionHeaderWriter;

import static org.springframework.security.config.Customizer.withDefaults;

@Configuration
@EnableWebSecurity
@EnableMethodSecurity(securedEnabled = true)
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class SecurityConfig {
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
    public SecurityFilterChain securityFilterChain(HttpSecurity http) throws Exception {
        http.cors(withDefaults())
            .headers(headers -> headers
                    .httpStrictTransportSecurity(withDefaults())
                    .xssProtection(xXssConfig -> xXssConfig
                            .headerValue(XXssProtectionHeaderWriter.HeaderValue.ENABLED_MODE_BLOCK))
                    .frameOptions(HeadersConfigurer.FrameOptionsConfig::sameOrigin)
                    .permissionsPolicyHeader(policy -> policy.policy("geolocation=(), microphone=(), camera=()")))
            .authorizeHttpRequests(authorize -> authorize
                    .requestMatchers(ApplicationConstants.getAuthWhitelist()).permitAll()
                    .anyRequest().authenticated())
            .formLogin(form -> form.defaultSuccessUrl("/", true))
            .logout(logout -> logout.logoutSuccessUrl("/"));

        return http.build();
    }

    @Bean
    public PasswordEncoder passwordEncoder() {
        return new Argon2PasswordEncoder(salt, length, parallelism, memory, iterations);
    }
}
