package com.barataribeiro.medicore.features.authentication;

import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.Authentication;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PostMapping;

import static com.barataribeiro.medicore.utils.ApplicationConstants.PAGE_TITLE;
import static com.barataribeiro.medicore.utils.ApplicationConstants.REGISTRATION_DTO;

@Controller
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class AuthController {
    private final AuthService authService;

    @GetMapping("/login")
    public String login(Model model, Authentication authentication) {
        if (authentication != null) return "redirect:/";
        model.addAttribute(PAGE_TITLE, "Login");
        return "pages/auth/login";
    }

    @GetMapping("/register")
    public String register(Model model) {
        model.addAttribute(PAGE_TITLE, "Register");
        model.addAttribute(REGISTRATION_DTO, new RegistrationDto());
        model.addAttribute("success", false);
        return "pages/auth/register";
    }

    @PostMapping("/register")
    public String register(Model model, @Valid @ModelAttribute RegistrationDto registrationDto,
                           BindingResult bindingResult) {

        String response = authService.registerUser(model, registrationDto, bindingResult);
        if (response != null) return response;

        model.addAttribute(REGISTRATION_DTO, new RegistrationDto());
        model.addAttribute(PAGE_TITLE, "Register");
        model.addAttribute("success", true);

        return "pages/auth/register";
    }
}
