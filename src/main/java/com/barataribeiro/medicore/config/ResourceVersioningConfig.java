package com.barataribeiro.medicore.config;

import org.jetbrains.annotations.NotNull;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.http.CacheControl;
import org.springframework.web.servlet.config.annotation.ResourceHandlerRegistry;
import org.springframework.web.servlet.config.annotation.WebMvcConfigurer;
import org.springframework.web.servlet.resource.ResourceUrlEncodingFilter;
import org.springframework.web.servlet.resource.VersionResourceResolver;

import java.util.concurrent.TimeUnit;

@Configuration
public class ResourceVersioningConfig implements WebMvcConfigurer {
    @Bean
    public ResourceUrlEncodingFilter resourceUrlEncodingFilter() {
        return new ResourceUrlEncodingFilter();
    }

    @Override
    public void addResourceHandlers(@NotNull ResourceHandlerRegistry handlerRegistry) {
        handlerRegistry.addResourceHandler("/**")
                       .addResourceLocations("classpath:/static/")
                       .addResourceLocations("classpath:/static/assets/")
                       .addResourceLocations("classpath:/static/css/")
                       .addResourceLocations("classpath:/static/js/")
                       .setCacheControl(CacheControl.maxAge(365, TimeUnit.DAYS))
                       .resourceChain(true)
                       .addResolver(new VersionResourceResolver().addContentVersionStrategy("/**"));
    }
}
