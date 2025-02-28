package com.barataribeiro.medicore.config.security;

import com.barataribeiro.medicore.utils.ApplicationConstants;
import jakarta.servlet.FilterChain;
import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.jetbrains.annotations.NotNull;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.authentication.AnonymousAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.session.data.redis.RedisIndexedSessionRepository;
import org.springframework.stereotype.Component;
import org.springframework.web.filter.OncePerRequestFilter;

import java.io.IOException;
import java.util.LinkedHashMap;
import java.util.Map;

@Slf4j
@Component
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class SessionMetadataFilter extends OncePerRequestFilter {

    private final RedisIndexedSessionRepository sessionRepository;

    @Override
    protected boolean shouldNotFilter(@NotNull HttpServletRequest request) {
        String path = request.getRequestURI();
        return path.startsWith("/static/") ||
                path.startsWith("/css/") ||
                path.startsWith("/js/") ||
                path.startsWith("/images/");
    }

    @Override
    protected void doFilterInternal(@NotNull HttpServletRequest request,
                                    @NotNull HttpServletResponse response,
                                    @NotNull FilterChain filterChain) throws ServletException, IOException {

        HttpSession httpSession = request.getSession(false);
        Authentication authentication = SecurityContextHolder.getContext().getAuthentication();

        // Check if session exists, user is authenticated, and SESSION_METADATA doesn't exist
        if (httpSession != null &&
                authentication != null &&
                authentication.isAuthenticated() &&
                !(authentication instanceof AnonymousAuthenticationToken) &&
                httpSession.getAttribute(ApplicationConstants.SESSION_METADATA) == null) {

            log.atInfo().log("Adding SESSION_METADATA for user {} in session {}",
                             authentication.getName(), httpSession.getId());

            Map<String, Object> sessionMetadata = new LinkedHashMap<>();
            sessionMetadata.put("userAgent", request.getHeader("User-Agent"));
            sessionMetadata.put("ipAddress", request.getRemoteAddr());
            sessionMetadata.put("loginTime", System.currentTimeMillis());
            sessionMetadata.put("authenticationType", "Remember-Me");

            httpSession.setAttribute(ApplicationConstants.SESSION_METADATA, sessionMetadata);

            try {
                RedisIndexedSessionRepository.RedisSession redisSession =
                        sessionRepository.findById(httpSession.getId());
                if (redisSession != null) {
                    redisSession.setAttribute(ApplicationConstants.SESSION_METADATA, sessionMetadata);
                    sessionRepository.save(redisSession);
                    log.atInfo().log("Session metadata saved to Redis for session {}", httpSession.getId());
                }
            } catch (Exception e) {
                log.atError().log("Error while saving session metadata: {}", e.getMessage());
            }
        }

        filterChain.doFilter(request, response);
    }
}