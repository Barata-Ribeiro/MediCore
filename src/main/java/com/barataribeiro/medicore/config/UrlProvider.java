package com.barataribeiro.medicore.config;

import lombok.RequiredArgsConstructor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;
import org.springframework.web.servlet.resource.ResourceUrlProvider;

@Component
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class UrlProvider {
    private final ResourceUrlProvider resourceUrlProvider;

    public String getVersionedUrl(String path) {
        return resourceUrlProvider.getForLookupPath(path);
    }
}
