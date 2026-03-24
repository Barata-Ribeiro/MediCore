package com.barataribeiro.medicore.features.global;

import jakarta.servlet.RequestDispatcher;
import jakarta.servlet.http.HttpServletRequest;
import org.springframework.boot.web.servlet.error.ErrorController;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.RequestMapping;

@Controller
public class CustomErrorController implements ErrorController {

    @RequestMapping("/error")
    public String handleError(HttpServletRequest request, Model model) {
        Object status = request.getAttribute(RequestDispatcher.ERROR_STATUS_CODE);

        int statusCode = 500;
        String errorMessage = "Unknown error";

        if (status != null) {
            statusCode = Integer.parseInt(status.toString());
            errorMessage = switch (statusCode) {
                case 403 -> "Access denied";
                case 404 -> "Page not found";
                case 500 -> "Internal server error";
                default -> "Unknown error";
            };
        }

        model.addAttribute("statusCode", statusCode);
        model.addAttribute("errorMessage", errorMessage);

        return "pages/error";
    }
}
