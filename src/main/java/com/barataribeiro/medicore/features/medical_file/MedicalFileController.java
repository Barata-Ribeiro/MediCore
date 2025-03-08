package com.barataribeiro.medicore.features.medical_file;

import com.barataribeiro.medicore.features.medical_file.dtos.UpdateMedicalFileDto;
import lombok.AllArgsConstructor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.security.core.Authentication;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;

import static com.barataribeiro.medicore.utils.ApplicationConstants.*;

@Controller
@RequestMapping("/{username}/medical-history")
@AllArgsConstructor(onConstructor_ = {@Autowired})
public class MedicalFileController {
    private final MedicalFileRepository medicalFileRepository;

    @GetMapping
    @PreAuthorize("#username == authentication.name")
    public String getMedicalFile(Authentication authentication, @PathVariable String username, Model model) {
        MedicalFile medicalFile = medicalFileRepository
                .findByUser_Username(username)
                .orElseThrow(() -> new RuntimeException("Medical File not found."));

        model.addAttribute(PAGE_TITLE, "Medical History");
        model.addAttribute(PAGE_DESCRIPTION, "View your medical history and add new medical records.");
        model.addAttribute("medicalFile", medicalFile);
        model.addAttribute(UPDATE_MEDICAL_FILE_DTO, new UpdateMedicalFileDto());
        model.addAttribute("success", false);
        return "pages/dashboard/medical_file/index";
    }
}
