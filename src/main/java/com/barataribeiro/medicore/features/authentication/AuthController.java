package com.barataribeiro.medicore.features.authentication;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;

@Controller
public class AuthController {

    //    @GetMapping("/login")
    //    public String login() {
    //        return "auth/register";
    //    }

    @GetMapping("/register")
    public String register() {
        return "auth/register";
    }
}
