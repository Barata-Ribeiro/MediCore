package com.barataribeiro.medicore.features.authentication;

import com.barataribeiro.medicore.features.user.AppUser;
import com.barataribeiro.medicore.features.user.AppUserRepository;
import jakarta.annotation.Nullable;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.jetbrains.annotations.NotNull;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.validation.FieldError;

import static com.barataribeiro.medicore.utils.ApplicationConstants.PAGE_TITLE;
import static com.barataribeiro.medicore.utils.ApplicationConstants.REGISTRATION_DTO;

@Slf4j
@Service
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class AuthService implements UserDetailsService {
    private final AppUserRepository appUserRepository;
    private final PasswordEncoder passwordEncoder;

    @Override
    public UserDetails loadUserByUsername(@NotNull String username) throws UsernameNotFoundException {
        return appUserRepository.findByUsername(username)
                                .orElseThrow(() -> new UsernameNotFoundException("User not found"));
    }


    public @Nullable String registerUser(Model model, @NotNull RegistrationDto registrationDto,
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

        try {
            AppUser newUser = AppUser.builder()
                                     .username(registrationDto.getUsername())
                                     .email(registrationDto.getEmail())
                                     .displayName(registrationDto.getDisplayName())
                                     .password(passwordEncoder.encode(registrationDto.getPassword()))
                                     .build();

            appUserRepository.save(newUser);
        } catch (Exception e) {
            bindingResult.addError(new FieldError(REGISTRATION_DTO, "username", e.getMessage()));
        }

        return null;
    }
}
