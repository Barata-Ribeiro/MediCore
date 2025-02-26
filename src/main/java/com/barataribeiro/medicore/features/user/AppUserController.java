package com.barataribeiro.medicore.features.user;

import lombok.RequiredArgsConstructor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;

import static com.barataribeiro.medicore.utils.ApplicationConstants.PAGE_TITLE;

@Controller
@RequestMapping("/{username}")
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class AppUserController {
    private final UserMapper userMapper;
    private final ProfileRepository profileRepository;

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
        return "pages/dashboard/profile/profile";
    }

    @GetMapping("/settings")
    @PreAuthorize("#username == authentication.name")
    public String settings(@PathVariable String username, Model model) {
        model.addAttribute(PAGE_TITLE, "Settings");
        return "pages/dashboard/profile/settings";
    }
}
