package com.barataribeiro.medicore.features.global;

import com.barataribeiro.medicore.helpers.BreadcrumbHelper;
import jakarta.servlet.http.HttpServletRequest;
import org.jetbrains.annotations.NotNull;
import org.springframework.beans.propertyeditors.StringTrimmerEditor;
import org.springframework.security.core.Authentication;
import org.springframework.web.bind.WebDataBinder;
import org.springframework.web.bind.annotation.ControllerAdvice;
import org.springframework.web.bind.annotation.InitBinder;
import org.springframework.web.bind.annotation.ModelAttribute;

@ControllerAdvice
public class GlobalController {

    @InitBinder
    public void initBinder(@NotNull WebDataBinder binder) {
        binder.registerCustomEditor(String.class, new StringTrimmerEditor(true));
    }

    @ModelAttribute("servletPath")
    String getRequestServletPath(@NotNull HttpServletRequest request) {
        return request.getServletPath();
    }

    @ModelAttribute("userBaseUrl")
    String getBaseAuthUrl(Authentication authentication) {
        return authentication != null ? ("/" + authentication.getName()) : "";
    }

    @ModelAttribute("breadcrumbHelper")
    public BreadcrumbHelper getBreadcrumbHelper() {
        return new BreadcrumbHelper();
    }
}
