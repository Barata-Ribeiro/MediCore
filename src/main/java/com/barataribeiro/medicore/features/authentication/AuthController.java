package com.barataribeiro.medicore.features.authentication;

import com.barataribeiro.medicore.features.user.AppUser;
import com.barataribeiro.medicore.features.user.AppUserRepository;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.validation.FieldError;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PostMapping;

import static com.barataribeiro.medicore.utils.ApplicationConstants.PAGE_TITLE;
import static com.barataribeiro.medicore.utils.ApplicationConstants.REGISTRATION_DTO;

@Controller
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class AuthController {
    private final AppUserRepository appUserRepository;
    private final PasswordEncoder passwordEncoder;

    @GetMapping("/login")
    public String login(Model model) {
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

        if (!registrationDto.getPassword().equals(registrationDto.getPasswordConfirmation())) {
            bindingResult
                    .addError(new FieldError(REGISTRATION_DTO, "passwordConfirmation", "Passwords do not match."));
        }

        if (appUserRepository.existsByUsername(registrationDto.getUsername())) {
            bindingResult
                    .addError(new FieldError(REGISTRATION_DTO, "username", "Username already in use."));
        }

        if (appUserRepository.existsByEmail(registrationDto.getEmail())) {
            bindingResult
                    .addError(new FieldError(REGISTRATION_DTO, "email", "Email already in use."));
        }

        if (bindingResult.hasErrors()) {
            model.addAttribute(PAGE_TITLE, "Register");
            return "pages/auth/register";
        }

        AppUser newUser = AppUser.builder()
                                 .username(registrationDto.getUsername())
                                 .email(registrationDto.getEmail())
                                 .displayName(registrationDto.getDisplayName())
                                 .password(passwordEncoder.encode(registrationDto.getPassword()))
                                 .build();

        appUserRepository.save(newUser);

        model.addAttribute(REGISTRATION_DTO, new RegistrationDto());
        model.addAttribute(PAGE_TITLE, "Register");
        model.addAttribute("success", true);

        return "pages/auth/register";
    }
}
