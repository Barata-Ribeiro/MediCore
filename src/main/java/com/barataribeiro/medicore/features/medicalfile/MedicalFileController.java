package com.barataribeiro.medicore.features.medicalfile;

import com.barataribeiro.medicore.features.exams.lipid_profile.LipidProfileService;
import com.barataribeiro.medicore.features.exams.lipid_profile.dtos.LipidProfileDto;
import org.springframework.data.domain.Page;
import org.springframework.security.core.Authentication;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import software.xdev.chartjs.model.charts.LineChart;

import static com.barataribeiro.medicore.utils.ApplicationConstants.PAGE_DESCRIPTION;
import static com.barataribeiro.medicore.utils.ApplicationConstants.PAGE_TITLE;

@Controller
@RequestMapping("/{username}/medical-history")
public class MedicalFileController {

    private final LipidProfileService lipidProfileService;

    public MedicalFileController(LipidProfileService lipidProfileService) {
        this.lipidProfileService = lipidProfileService;
    }

    @GetMapping
    public String getMedicalFile(Authentication authentication, @PathVariable String username) {
        if (!username.equals(authentication.getName())) return "redirect:/";
        return "pages/dashboard/medical_file/index";
    }

    @GetMapping("/lipid-profile")
    public String getLipidProfile(Model model, @RequestParam(defaultValue = "0") int page,
                                  @RequestParam(defaultValue = "75") int perPage,
                                  @RequestParam(defaultValue = "ASC") String direction,
                                  @RequestParam(defaultValue = "reportDate") String orderBy,
                                  Authentication authentication, @PathVariable String username) {
        if (!username.equals(authentication.getName())) return "redirect:/";
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
        return "pages/dashboard/medical_file/lipid-profile";
    }

    @PostMapping("/lipid-profile")
    public String postLipidProfile(Authentication authentication, @PathVariable String username) {
        if (!username.equals(authentication.getName())) return "redirect:/";
        return "redirect:/" + username + "/medical-history/lipid-profile";
    }
}
