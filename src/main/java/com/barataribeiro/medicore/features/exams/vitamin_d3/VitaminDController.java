package com.barataribeiro.medicore.features.exams.vitamin_d3;

import com.barataribeiro.medicore.features.exams.vitamin_d3.dtos.NewVitaminDProfileDto;
import com.barataribeiro.medicore.features.exams.vitamin_d3.dtos.VitaminDDto;
import jakarta.validation.Valid;
import lombok.AllArgsConstructor;
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
@AllArgsConstructor(onConstructor_ = {@Autowired})
public class VitaminDController {
    private final VitaminDService vitaminDService;
    private final VitaminDRepository vitaminDRepository;

    @GetMapping("/vitamind")
    @PreAuthorize("#username == authentication.name")
    public String getVitaminD(Model model, @RequestParam(defaultValue = "0") int page,
                              @RequestParam(defaultValue = "10") int perPage,
                              @RequestParam(defaultValue = "DESC") String direction,
                              @RequestParam(defaultValue = "reportDate") String orderBy,
                              Authentication authentication, @PathVariable String username) {
        Page<VitaminDDto> response = vitaminDService.getVitaminDPaginated(page, perPage, direction, orderBy,
                                                                          authentication);
        BarChart chart = vitaminDService.getVitaminDChartInfo(response.getContent());

        model.addAttribute(PAGE_TITLE, "Vitamin D3 Profile");
        model.addAttribute(PAGE_DESCRIPTION, "Check your vitamin D3 profile");
        model.addAttribute("vitaminDs", response.getContent());
        model.addAttribute("currentPage", response.getNumber());
        model.addAttribute("totalPages", response.getTotalPages());
        model.addAttribute("totalItems", response.getTotalElements());
        model.addAttribute("vitaminDChart", chart.toJson());
        return "pages/dashboard/medical_file/vitamin_d/vitamin_d";
    }

    @GetMapping("/vitamind/add")
    @PreAuthorize("#username == authentication.name")
    public String newLipidProfile(Model model, @PathVariable String username) {
        model.addAttribute(PAGE_TITLE, "New Vitamin D3 Profile");
        model.addAttribute(PAGE_DESCRIPTION, "Add a new Vitamin D3 profile");
        model.addAttribute(NEW_VITAMIND_PROFILE_DTO, new NewVitaminDProfileDto());
        return "pages/dashboard/medical_file/glucose/glucose-level-add";
    }

    @PostMapping("/vitamind/add")
    @PreAuthorize("#username == authentication.name")
    public String newGlucoseLevel(Model model, @PathVariable String username,
                                  @Valid @ModelAttribute NewVitaminDProfileDto newVitaminDProfileDto) {
        vitaminDService.addVitaminD(newVitaminDProfileDto, username);
        return "redirect:/" + username + "/medical-history/vitamind";
    }

    @DeleteMapping("/vitamind/{id}/delete")
    @PreAuthorize("#username == authentication.name")
    public String deleteLipidProfile(@PathVariable String username, @PathVariable Long id) {
        vitaminDRepository.deleteByIdAndMedicalFile_User_Username(id, username);
        return "redirect:/" + username + "/medical-history/vitamind";
    }
}
