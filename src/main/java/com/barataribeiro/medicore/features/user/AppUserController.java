package com.barataribeiro.medicore.features.user;

import com.barataribeiro.medicore.features.user.dtos.UpdateAppUserDto;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;

import static com.barataribeiro.medicore.utils.ApplicationConstants.PAGE_TITLE;
import static com.barataribeiro.medicore.utils.ApplicationConstants.UPDATE_APP_USER_DTO;

@Controller
@RequestMapping("/{username}")
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class AppUserController {
    private final UserMapper userMapper;
    private final ProfileRepository profileRepository;
    private final AppUserService appUserService;

    @GetMapping
    @PreAuthorize("#username == authentication.name")
    public String dashboard(@PathVariable String username, Model model) {
        model.addAttribute(PAGE_TITLE, username);
        return "pages/dashboard/index";
    }

    @GetMapping("/profile")
    @PreAuthorize("#username == authentication.name")
    public String profile(@PathVariable String username, Model model) {
        Profile userProfile = profileRepository.findByUser_Username(username).orElseThrow();

        model.addAttribute(PAGE_TITLE, username);
        model.addAttribute("profile", userMapper.toUserProfileDto(userProfile));
        model.addAttribute(UPDATE_APP_USER_DTO, new UpdateAppUserDto());
        model.addAttribute("success", false);
        return "pages/dashboard/profile/profile";
    }

    @PostMapping("/profile")
    @PreAuthorize("#username == authentication.name")
    public String updateProfile(@PathVariable String username, Model model,
                                @Valid @ModelAttribute UpdateAppUserDto updateAppUserDto, BindingResult bindingResult) {

        String response = appUserService.updateAppUser(username, model, updateAppUserDto, bindingResult);
        if (response != null) return response;

        model.addAttribute(PAGE_TITLE, username);
        model.addAttribute(UPDATE_APP_USER_DTO, new UpdateAppUserDto());
        model.addAttribute("success", true);

        return "pages/dashboard/profile/profile";
    }

    @GetMapping("/settings")
    @PreAuthorize("#username == authentication.name")
    public String settings(@PathVariable String username, Model model) {
        model.addAttribute(PAGE_TITLE, "Settings");
        return "pages/dashboard/profile/settings";
    }
}
