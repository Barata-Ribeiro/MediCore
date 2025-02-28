package com.barataribeiro.medicore.config.security;

import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.jetbrains.annotations.NotNull;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.Authentication;
import org.springframework.security.web.authentication.AuthenticationSuccessHandler;
import org.springframework.session.data.redis.RedisIndexedSessionRepository;
import org.springframework.stereotype.Component;

import java.io.IOException;
import java.util.LinkedHashMap;
import java.util.Map;

@Slf4j
@Component
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class LoginSuccessHandler implements AuthenticationSuccessHandler {
    private final RedisIndexedSessionRepository sessionRepository;

    @Override
    public void onAuthenticationSuccess(@NotNull HttpServletRequest request, HttpServletResponse response,
                                        Authentication authentication) throws IOException, ServletException {
        Map<String, Object> sessionMetadata = new LinkedHashMap<>();
        sessionMetadata.put("userAgent", request.getHeader("User-Agent"));
        sessionMetadata.put("ipAddress", request.getRemoteAddr());
        sessionMetadata.put("loginTime", System.currentTimeMillis());
        sessionMetadata.put("authenticationType", "Login");

        HttpSession httpSession = request.getSession(false);

        if (httpSession != null) {
            httpSession.setAttribute("SESSION_METADATA", sessionMetadata);

            try {
                RedisIndexedSessionRepository.RedisSession redisSession = sessionRepository.findById(
                        httpSession.getId());
                if (redisSession != null) {
                    redisSession.setAttribute("SESSION_METADATA", sessionMetadata);
                    sessionRepository.save(redisSession);
                    log.atInfo().log("Session metadata saved: {}", sessionMetadata);
                }
            } catch (Exception e) {
                log.atError().log("Error while saving session metadata: {}", e.getMessage());
            }
        }

        String targetUrl = "/" + authentication.getName();
        response.sendRedirect(targetUrl);
    }
}
