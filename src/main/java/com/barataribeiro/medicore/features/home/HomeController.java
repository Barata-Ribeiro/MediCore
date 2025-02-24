package com.barataribeiro.medicore.features.home;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;

import static com.barataribeiro.medicore.utils.ApplicationConstants.PAGE_TITLE;

@Controller
public class HomeController {
    @GetMapping({"", "/", "/home"})
    public String home(Model model) {
        model.addAttribute(PAGE_TITLE, "Home");
        return "pages/home/home";
    }

    @GetMapping("/about")
    public String about(Model model) {
        model.addAttribute(PAGE_TITLE, "About");
        return "pages/home/about";
    }
}
