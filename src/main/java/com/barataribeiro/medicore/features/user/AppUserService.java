package com.barataribeiro.medicore.features.user;

import com.barataribeiro.medicore.features.user.dtos.UpdateAppUserDto;
import jakarta.annotation.Nullable;
import lombok.RequiredArgsConstructor;
import org.jetbrains.annotations.NotNull;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.validation.FieldError;

import java.util.Date;

import static com.barataribeiro.medicore.utils.ApplicationConstants.PAGE_TITLE;
import static com.barataribeiro.medicore.utils.ApplicationConstants.UPDATE_APP_USER_DTO;

@Service
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class AppUserService {
    private final ProfileRepository profileRepository;
    private final PasswordEncoder passwordEncoder;
    private final AppUserRepository appUserRepository;
    private final UserMapper userMapper;

    @Transactional
    public @Nullable String updateAppUser(String username, Model model, @NotNull UpdateAppUserDto body,
                                          BindingResult bindingResult) {
        Profile profile = profileRepository.findByUser_Username(username)
                                           .orElseThrow(() -> new RuntimeException("Profile not found"));
        AppUser user = profile.getUser();

        boolean passwordMatches = passwordEncoder.matches(body.getCurrentPassword(), user.getPassword());
        if (!passwordMatches) bindingResult.addError(new FieldError(UPDATE_APP_USER_DTO, "currentPassword",
                                                                    "You provided an incorrect password"));

        verifyIfBodyExistsThenUpdateProperties(body, bindingResult, user, profile);

        if (bindingResult.hasErrors()) {
            model.addAttribute(PAGE_TITLE, username);
            model.addAttribute("profile", profile);
            model.addAttribute(UPDATE_APP_USER_DTO, body);
            model.addAttribute("success", false);
            return "pages/dashboard/profile/profile";
        }

        appUserRepository.save(user);

        model.addAttribute("profile", userMapper.toUserProfileDto(profileRepository.saveAndFlush(profile)));

        return null;
    }

    private void verifyIfBodyExistsThenUpdateProperties(@NotNull UpdateAppUserDto body, BindingResult bindingResult,
                                                        @NotNull AppUser user, @NotNull Profile profile) {
        updateDisplayName(body.getDisplayName(), bindingResult, user);
        updateEmail(body.getEmail(), bindingResult, user);
        updatePassword(body.getNewPassword(), body.getPasswordConfirmation(), bindingResult, user);
        updateFirstName(body.getFirstName(), bindingResult, profile);
        updateLastName(body.getLastName(), bindingResult, profile);
        updateBirthDate(body.getBirthDate(), bindingResult, profile);
        updateAvatarUrl(body.getAvatarUrl(), bindingResult, user);
        updateSex(body.getSex(), bindingResult, profile);
        updateTitle(body.getTitle(), bindingResult, profile);
        updateBiography(body.getBiography(), bindingResult, profile);
    }

    private void updateDisplayName(String displayName, BindingResult bindingResult, AppUser user) {
        if (displayName != null && !bindingResult.hasErrors()) user.setDisplayName(displayName);
    }

    private void updateEmail(String email, BindingResult bindingResult, AppUser user) {
        if (email != null) {
            if (email.equals(user.getEmail())) {
                bindingResult.addError(new FieldError(UPDATE_APP_USER_DTO, "email", "You already use this email."));
            } else if (appUserRepository.existsByEmail(email)) {
                bindingResult.addError(new FieldError(UPDATE_APP_USER_DTO, "email", "Email already in use."));
            }

            if (!bindingResult.hasErrors()) user.setEmail(email);
        }
    }

    private void updatePassword(String newPassword, String confirmation, BindingResult bindingResult, AppUser user) {
        if (newPassword != null) {
            if (confirmation == null || confirmation.isBlank()) {
                bindingResult.addError(new FieldError(UPDATE_APP_USER_DTO, "passwordConfirmation",
                                                      "You must confirm your new password."));
            } else if (!newPassword.equals(confirmation)) {
                bindingResult.addError(new FieldError(UPDATE_APP_USER_DTO, "passwordConfirmation",
                                                      "The passwords do not match."));
            }

            if (!bindingResult.hasErrors()) user.setPassword(passwordEncoder.encode(newPassword));
        }
    }

    private void updateFirstName(String firstName, BindingResult bindingResult, Profile profile) {
        if (firstName != null && !bindingResult.hasErrors()) profile.setFirstName(firstName);
    }

    private void updateLastName(String lastName, BindingResult bindingResult, Profile profile) {
        if (lastName != null && !bindingResult.hasErrors()) profile.setLastName(lastName);
    }

    private void updateBirthDate(Date birthDate, BindingResult bindingResult, Profile profile) {
        if (birthDate != null && !bindingResult.hasErrors()) profile.setBirthDate(birthDate);
    }

    private void updateAvatarUrl(String avatarUrl, BindingResult bindingResult, AppUser user) {
        if (avatarUrl != null && !bindingResult.hasErrors()) user.setAvatarUrl(avatarUrl);
    }

    private void updateSex(String sex, BindingResult bindingResult, Profile profile) {
        if (sex != null && !bindingResult.hasErrors()) profile.setSex(sex);
    }

    private void updateTitle(String title, BindingResult bindingResult, Profile profile) {
        if (title != null && !bindingResult.hasErrors()) profile.setTitle(title);
    }

    private void updateBiography(String biography, BindingResult bindingResult, Profile profile) {
        if (biography != null && !bindingResult.hasErrors()) profile.setBiography(biography);
    }
}
