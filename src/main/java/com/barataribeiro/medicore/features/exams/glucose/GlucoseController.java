package com.barataribeiro.medicore.features.exams.glucose;

import com.barataribeiro.medicore.features.exams.glucose.dtos.GlucoseDto;
import com.barataribeiro.medicore.features.exams.glucose.dtos.NewGlucoseDto;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.security.core.Authentication;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import software.xdev.chartjs.model.charts.BarChart;

import static com.barataribeiro.medicore.utils.ApplicationConstants.*;

@Controller
@RequestMapping("/{username}/medical-history")
@RequiredArgsConstructor(onConstructor = @__(@Autowired))
class GlucoseController {

    private final GlucoseService glucoseService;
    private final GlucoseRepository glucoseRepository;

    @GetMapping("/glucose-level")
    @PreAuthorize("#username == authentication.name")
    String getGlucose(Model model, @RequestParam(defaultValue = "0") int page,
                      @RequestParam(defaultValue = "10") int perPage,
                      @RequestParam(defaultValue = "DESC") String direction,
                      @RequestParam(defaultValue = "reportDate") String orderBy,
                      Authentication authentication, @PathVariable String username) {
        Page<GlucoseDto> response = glucoseService
                .getGlucosePaginated(page, perPage, direction, orderBy, authentication);
        BarChart chart = glucoseService.getGlucoseChart(response.getContent());

        model.addAttribute(PAGE_TITLE, "Glucose");
        model.addAttribute(PAGE_DESCRIPTION, "Check your glucose levels");
        model.addAttribute("glucoses", response.getContent());
        model.addAttribute("currentPage", response.getNumber());
        model.addAttribute("totalPages", response.getTotalPages());
        model.addAttribute("totalItems", response.getTotalElements());
        model.addAttribute("glucoseChart", chart.toJson());
        return "pages/dashboard/medical_file/glucose/glucose-level";
    }

    @GetMapping("/glucose-level/add")
    @PreAuthorize("#username == authentication.name")
    public String getGlucoseProfile(Model model, @PathVariable String username) {
        model.addAttribute(PAGE_TITLE, "New Glucose Level Profile");
        model.addAttribute(PAGE_DESCRIPTION, "Add a new glucose level profile");
        model.addAttribute(NEW_GLUCOSE_DTO, new NewGlucoseDto());
        return "pages/dashboard/medical_file/glucose/glucose-level-add";
    }

    @PostMapping("/glucose-level/add")
    @PreAuthorize("#username == authentication.name")
    public String newGlucoseProfile(Model model, @PathVariable String username,
                                    @Valid @ModelAttribute NewGlucoseDto newGlucoseDto) {
        glucoseService.addGlucoseProfile(newGlucoseDto, username);
        return "redirect:/" + username + "/medical-history/glucose-level";
    }

    @DeleteMapping("/glucose-level/{id}/delete")
    @PreAuthorize("#username == authentication.name")
    public String deleteGlucoseProfile(@PathVariable String username, @PathVariable Long id) {
        glucoseRepository.deleteByIdAndMedicalFile_User_Username(id, username);
        return "redirect:/" + username + "/medical-history/glucose-level";
    }
}
