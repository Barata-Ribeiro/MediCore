package com.barataribeiro.medicore.utils;

public class ApplicationConstants {
    public static final String PAGE_TITLE = "pageTitle";
    public static final String PAGE_DESCRIPTION = "pageDescription";

    public static final String NEW_CBC_DTO = "newCBCDto";
    public static final String NEW_GLUCOSE_DTO = "newGlucoseDto";
    public static final String NEW_LIPID_PROFILE_DTO = "newLipidProfileDto";
    public static final String NEW_ULTRASENSITIVE_TSH_PROFILE_DTO = "newUltrasensitiveTSHProfileDto";
    public static final String NEW_UREA_AND_CREATININE_PROFILE_DTO = "newUreaAndCreatinineProfileDto";
    public static final String NEW_URIC_ACID_PROFILE_DTO = "newUricAcidProfileDto";
    public static final String NEW_VITAMIND_PROFILE_DTO = "newVitaminDProfileDto";
    public static final String NEW_VITAMIN_B_TWELVE_PROFILE_DTO = "newVitaminBTwelveProfileDto";
    public static final String REGISTRATION_DTO = "registrationDto";
    public static final String UPDATE_APP_USER_DTO = "updateAppUserDto";
    public static final String UPDATE_MEDICAL_FILE_DTO = "updateMedicalFileDto";

    public static final String CREATED_AT = "createdAt";
    public static final String SESSION_METADATA = "SESSION_METADATA";
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

            // -- Application Resources
            "/actuator/**",
            "/assets/**",
            "/css/**",
            "/favicon.ico",
            "/h2-console/**",
            "/js/**",
            "/resources/**",
            "/static/**",
            "/templates/**",

            // -- Application Routes
            "/",
            "/about",
            "/error",
            "/login",
            "/logout",
            "/register"
    };

    private ApplicationConstants() {
        throw new UnsupportedOperationException("This class cannot be instantiated.");
    }

    public static String[] getAuthWhitelist() {
        return AUTH_WHITELIST.clone();
    }
}
