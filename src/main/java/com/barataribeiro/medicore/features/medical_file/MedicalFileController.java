package com.barataribeiro.medicore.features.medical_file;

import com.barataribeiro.medicore.features.medical_file.dtos.CompleteMedicalFileDto;
import com.barataribeiro.medicore.features.medical_file.dtos.UpdateMedicalFileDto;
import jakarta.validation.Valid;
import lombok.AllArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;

import static com.barataribeiro.medicore.utils.ApplicationConstants.*;

@Slf4j
@Controller
@RequestMapping("/{username}/medical-history")
@AllArgsConstructor(onConstructor_ = {@Autowired})
public class MedicalFileController {
    private final MedicalFileRepository medicalFileRepository;
    private final MedicalFileMapper medicalFileMapper;
    private final MedicalFileService medicalFileService;

    @GetMapping
    @PreAuthorize("#username == authentication.name")
    public String getMedicalFile(@PathVariable String username, Model model) {
        MedicalFile medicalFile = medicalFileRepository
                .findMedicalFileByUser_UsernameWithLatestExams(username)
                .orElseThrow(() -> new RuntimeException("Medical File not found."));

        CompleteMedicalFileDto completeMedicalFile = medicalFileMapper.toCompleteMedicalFileDto(medicalFile);

        model.addAttribute(PAGE_TITLE, "Medical History");
        model.addAttribute(PAGE_DESCRIPTION, "View your medical history and add new medical records.");
        model.addAttribute("medicalFile", completeMedicalFile);
        model.addAttribute(UPDATE_MEDICAL_FILE_DTO, new UpdateMedicalFileDto());
        model.addAttribute("success", false);
        return "pages/dashboard/medical_file/index";
    }

    @PatchMapping
    @PreAuthorize("#username == authentication.name")
    public String updateMedicalFile(@PathVariable String username, Model model,
                                    @Valid @ModelAttribute UpdateMedicalFileDto updateMedicalFileDto,
                                    BindingResult bindingResult) {

        String response = medicalFileService.updateMedicalFile(username, model, updateMedicalFileDto, bindingResult);
        if (response != null) return response;

        model.addAttribute(PAGE_TITLE, "Medical History");
        model.addAttribute(PAGE_DESCRIPTION, "View your medical history and add new medical records.");
        model.addAttribute(UPDATE_MEDICAL_FILE_DTO, new UpdateMedicalFileDto());
        model.addAttribute("success", true);
        return "pages/dashboard/medical_file/index";
    }
}
