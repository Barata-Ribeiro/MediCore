package com.barataribeiro.medicore.features.medical_file;

import com.barataribeiro.medicore.features.medical_file.dtos.UpdateMedicalFileDto;
import jakarta.annotation.Nullable;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.jetbrains.annotations.NotNull;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;

import static com.barataribeiro.medicore.utils.ApplicationConstants.*;

@Slf4j
@Service
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class MedicalFileService {

    private final MedicalFileRepository medicalFileRepository;
    private final MedicalFileMapper medicalFileMapper;

    @Transactional
    public @Nullable String updateMedicalFile(String username, Model model,
                                              @NotNull UpdateMedicalFileDto updateMedicalFileDto,
                                              @NotNull BindingResult bindingResult) {
        MedicalFile medicalFile = medicalFileRepository
                .findMedicalFileByUser_UsernameWithLatestExams(username)
                .orElseThrow(() -> new RuntimeException("Medical File not found."));

        verifyIfBodyExistsThenUpdateProperties(updateMedicalFileDto, bindingResult, medicalFile);

        log.atInfo().log("Medical file for user {} updated with data: {}", username, updateMedicalFileDto);
        log.atInfo().log("Medical File: {}", medicalFile);

        if (bindingResult.hasErrors()) {
            model.addAttribute(PAGE_TITLE, "Medical History");
            model.addAttribute(PAGE_DESCRIPTION, "View your medical history and add new medical records.");
            model.addAttribute("medicalFile", medicalFileMapper.toCompleteMedicalFileDto(medicalFile));
            model.addAttribute(UPDATE_MEDICAL_FILE_DTO, updateMedicalFileDto);
            model.addAttribute("success", false);
            return "pages/dashboard/medical_file/index";
        }

        MedicalFile savedMedicalFile = medicalFileRepository.save(medicalFile);

        model.addAttribute("medicalFile", medicalFileMapper.toCompleteMedicalFileDto(savedMedicalFile));

        return null;
    }

    private void verifyIfBodyExistsThenUpdateProperties(@NotNull UpdateMedicalFileDto updateMedicalFileDto,
                                                        @NotNull BindingResult bindingResult, MedicalFile medicalFile) {
        if (updateMedicalFileDto.getBloodType() != null && !bindingResult.hasErrors()) {
            medicalFile.setBloodType(updateMedicalFileDto.getBloodType());
        }

        if (updateMedicalFileDto.getAllergies() != null && !bindingResult.hasErrors()) {
            medicalFile.setAllergies(updateMedicalFileDto.getAllergies());
        }

        if (updateMedicalFileDto.getDiseases() != null && !bindingResult.hasErrors()) {
            medicalFile.setDiseases(updateMedicalFileDto.getDiseases());
        }

        if (updateMedicalFileDto.getMedications() != null && !bindingResult.hasErrors()) {
            medicalFile.setMedications(updateMedicalFileDto.getMedications());
        }

        if (updateMedicalFileDto.getWeight() != null && !bindingResult.hasErrors()) {
            medicalFile.setWeight(updateMedicalFileDto.getWeight());
        }

        if (updateMedicalFileDto.getHeight() != null && !bindingResult.hasErrors()) {
            medicalFile.setHeight(updateMedicalFileDto.getHeight());
        }

        if (updateMedicalFileDto.getEmergencyContactName() != null && !bindingResult.hasErrors()) {
            medicalFile.setEmergencyContactName(updateMedicalFileDto.getEmergencyContactName());
        }

        if (updateMedicalFileDto.getEmergencyContactPhone() != null && !bindingResult.hasErrors()) {
            medicalFile.setEmergencyContactPhone(updateMedicalFileDto.getEmergencyContactPhone());
        }
    }
}
