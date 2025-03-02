package com.barataribeiro.medicore.features.exams.lipid_profile;

import com.barataribeiro.medicore.features.exams.lipid_profile.dtos.LipidProfileDto;
import com.barataribeiro.medicore.features.exams.lipid_profile.dtos.NewLipidProfileDto;
import jakarta.validation.Valid;
import lombok.AllArgsConstructor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.security.core.Authentication;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;
import software.xdev.chartjs.model.charts.LineChart;

import static com.barataribeiro.medicore.utils.ApplicationConstants.*;

@Controller
@RequestMapping("/{username}/medical-history")
@AllArgsConstructor(onConstructor_ = {@Autowired})
public class LipidProfileController {
    private final LipidProfileService lipidProfileService;
    private final LipidProfileRepository lipidProfileRepository;
    
    @GetMapping("/lipid-profile")
    @PreAuthorize("#username == authentication.name")
    public String getLipidProfile(Model model, @RequestParam(defaultValue = "0") int page,
                                  @RequestParam(defaultValue = "10") int perPage,
                                  @RequestParam(defaultValue = "DESC") String direction,
                                  @RequestParam(defaultValue = "reportDate") String orderBy,
                                  Authentication authentication, @PathVariable String username) {
        Page<LipidProfileDto> response = lipidProfileService.getLipidProfilePaginated(page, perPage, direction, orderBy,
                                                                                      authentication);

        LineChart chart = lipidProfileService.getLipidProfileChartInfo(response.getContent());

        model.addAttribute(PAGE_TITLE, "Lipid Profile");
        model.addAttribute(PAGE_DESCRIPTION, "Check your lipid profile");
        model.addAttribute("lipidProfiles", response.getContent());
        model.addAttribute("currentPage", response.getNumber());
        model.addAttribute("totalPages", response.getTotalPages());
        model.addAttribute("totalItems", response.getTotalElements());
        model.addAttribute("lipidChart", chart.toJson());
        return "pages/dashboard/medical_file/lipid_profile/lipid-profile";
    }

    @GetMapping("/lipid-profile/add")
    @PreAuthorize("#username == authentication.name")
    public String newLipidProfile(Model model, @PathVariable String username) {
        model.addAttribute(PAGE_TITLE, "New Lipid Profile");
        model.addAttribute(PAGE_DESCRIPTION, "Add a new lipid profile to your medical history");
        model.addAttribute(NEW_LIPID_PROFILE_DTO, new NewLipidProfileDto());
        return "pages/dashboard/medical_file/lipid_profile/lipid-profile-add";
    }

    @PostMapping("/lipid-profile/add")
    @PreAuthorize("#username == authentication.name")
    public String newLipidProfile(Model model, @PathVariable String username,
                                  @Valid @ModelAttribute NewLipidProfileDto newLipidProfileDto,
                                  BindingResult bindingResult) {
        lipidProfileService.addLipidProfile(newLipidProfileDto, username, model, bindingResult);
        return "redirect:/" + username + "/medical-history/lipid-profile";
    }

    @DeleteMapping("/lipid-profile/{id}/delete")
    @PreAuthorize("#username == authentication.name")
    public String deleteLipidProfile(@PathVariable String username, @PathVariable Long id) {
        lipidProfileRepository.deleteByIdAndMedicalFile_User_Username(id, username);
        return "redirect:/" + username + "/medical-history/lipid-profile";
    }
}
