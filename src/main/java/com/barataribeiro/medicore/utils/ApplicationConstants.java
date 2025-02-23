package com.barataribeiro.medicore.utils;

public class ApplicationConstants {

    private static final String[] AUTH_WHITELIST = {
            // -- Swagger UI v2
            "/v2/api-docs",
            "/swagger-resources",
            "/swagger-resources/**",
            "/configuration/ui",
            "/configuration/security",
            "/swagger-ui.html",
            "/webjars/**",
            // -- Swagger UI v3 (OpenAPI)
            "/api-docs",
            "/api-docs/**",
            "/v3/api-docs",
            "/v3/api-docs/**",
            "/swagger-ui/**",
            // -- Application
            "/",
            "/favicon.ico",
            "/resources/**",
            "/static/**",
            "/templates/**",
            "/css/**",
            "/js/**",
            "/assets/**",
            "/h2-console/**",
            "/actuator/**",
            "/register",
            "/login",
            "/logout",
            };

    private ApplicationConstants() {
        throw new UnsupportedOperationException("This class cannot be instantiated.");
    }

    public static String[] getAuthWhitelist() {
        return AUTH_WHITELIST.clone();
    }
}
