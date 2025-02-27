package com.barataribeiro.medicore.config.security;

import lombok.extern.slf4j.Slf4j;
import org.jetbrains.annotations.NotNull;
import org.springframework.context.event.EventListener;
import org.springframework.session.events.SessionCreatedEvent;
import org.springframework.session.events.SessionDeletedEvent;
import org.springframework.session.events.SessionDestroyedEvent;
import org.springframework.session.events.SessionExpiredEvent;
import org.springframework.stereotype.Component;

@Slf4j
@Component
public class SessionEventListener {
    @EventListener
    public void processSessionCreatedEvent(@NotNull SessionCreatedEvent event) {
        log.atInfo().log("Session created: {}", event.getSessionId());
    }

    @EventListener
    public void processSessionDeletedEvent(@NotNull SessionDeletedEvent event) {
        log.atInfo().log("Session deleted: {}", event.getSessionId());
    }

    @EventListener
    public void processSessionDestroyedEvent(@NotNull SessionDestroyedEvent event) {
        log.atInfo().log("Session destroyed: {}", event.getSessionId());
    }

    @EventListener
    public void processSessionExpiredEvent(@NotNull SessionExpiredEvent event) {
        log.atInfo().log("Session expired: {}", event.getSessionId());
    }

}
