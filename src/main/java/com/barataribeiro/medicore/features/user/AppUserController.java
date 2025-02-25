package com.barataribeiro.medicore.features.user;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;

import static com.barataribeiro.medicore.utils.ApplicationConstants.PAGE_TITLE;

@Controller
public class AppUserController {

    @GetMapping("/{username}/settings")
    public String settings(@PathVariable String username, Model model) {
        model.addAttribute(PAGE_TITLE, "Settings");
        return "pages/dashboard/profile/settings";
    }
}
