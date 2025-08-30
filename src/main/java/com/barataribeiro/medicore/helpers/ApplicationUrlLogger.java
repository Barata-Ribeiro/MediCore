package com.barataribeiro.medicore.helpers;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.boot.context.event.ApplicationReadyEvent;
import org.springframework.boot.web.context.WebServerApplicationContext;
import org.springframework.context.ApplicationListener;
import org.springframework.core.env.Environment;
import org.springframework.stereotype.Component;

import java.net.InetAddress;
import java.net.UnknownHostException;

@Component
public class ApplicationUrlLogger implements ApplicationListener<ApplicationReadyEvent> {
    private static final Logger log = LoggerFactory.getLogger(ApplicationUrlLogger.class);


    @Override
    public void onApplicationEvent(ApplicationReadyEvent event) {
        WebServerApplicationContext ctx = (WebServerApplicationContext) event.getApplicationContext();
        int port = ctx.getWebServer().getPort();
        Environment env = event.getApplicationContext().getEnvironment();

        String contextPath = env.getProperty("server.servlet.context-path", "");
        if ("/".equals(contextPath)) contextPath = "";

        String host = env.getProperty("server.address");
        if (host == null || host.isBlank()) {
            try {
                host = InetAddress.getLocalHost().getHostAddress();
            } catch (UnknownHostException e) {
                log.atError().log("The host name could not be determined, using 'localhost' as fallback");
                log.atError().log(e.getMessage());
                host = "localhost";
            }
        }

        boolean ssl = Boolean.parseBoolean(env.getProperty("server.ssl.enabled", "false"));
        String scheme = ssl ? "https" : "http";

        String url = scheme + "://" + host + ":" + port + contextPath;
        log.atInfo().log("Application started at: {}", url);
    }
}
