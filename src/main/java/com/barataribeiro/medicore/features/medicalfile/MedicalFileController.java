package com.barataribeiro.medicore.features.medicalfile;

import lombok.AllArgsConstructor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.security.core.Authentication;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;

@Controller
@RequestMapping("/{username}/medical-history")
@AllArgsConstructor(onConstructor_ = {@Autowired})
public class MedicalFileController {
    @GetMapping
    @PreAuthorize("#username == authentication.name")
    public String getMedicalFile(Authentication authentication, @PathVariable String username) {
        return "pages/dashboard/medical_file/index";
    }
}
