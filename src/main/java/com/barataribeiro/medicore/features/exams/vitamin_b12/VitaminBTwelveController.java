package com.barataribeiro.medicore.features.exams.vitamin_b12;

import com.barataribeiro.medicore.features.exams.vitamin_b12.dtos.NewVitaminBTwelveProfileDto;
import com.barataribeiro.medicore.features.exams.vitamin_b12.dtos.VitaminBTwelveDto;
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
public class VitaminBTwelveController {

    private final VitaminBTwelveRepository vitaminBTwelveRepository;
    private final VitaminBTwelveService vitaminBTwelve;

    @GetMapping("/vitamin-b-twelve")
    @PreAuthorize("#username == authentication.name")
    public String getVitaminBTwelve(Model model, @RequestParam(defaultValue = "0") int page,
                                    @RequestParam(defaultValue = "10") int perPage,
                                    @RequestParam(defaultValue = "DESC") String direction,
                                    @RequestParam(defaultValue = "reportDate") String orderBy,
                                    Authentication authentication, @PathVariable String username) {
        Page<VitaminBTwelveDto> response = vitaminBTwelve.getVitaminBTwelvePaginated(page, perPage, direction, orderBy,
                                                                                     authentication);
        BarChart chart = vitaminBTwelve.getVitaminBTwelveChartInfo(response.getContent());

        model.addAttribute(PAGE_TITLE, "Vitamin B12 Profile");
        model.addAttribute(PAGE_DESCRIPTION, "Check your vitamin B12 profile");
        model.addAttribute("vitaminBTwelves", response.getContent());
        model.addAttribute("currentPage", response.getNumber());
        model.addAttribute("totalPages", response.getTotalPages());
        model.addAttribute("totalItems", response.getTotalElements());
        model.addAttribute("vitaminBTwelveChart", chart.toJson());
        return "pages/dashboard/medical_file/vitamin_b_twelve/vitamin_b_twelve";
    }

    @GetMapping("/vitamin-b-twelve/add")
    @PreAuthorize("#username == authentication.name")
    public String newVitaminDProfile(Model model, @PathVariable String username) {
        model.addAttribute(PAGE_TITLE, "New Vitamin B12 Profile");
        model.addAttribute(PAGE_DESCRIPTION, "Add a new Vitamin B12 profile");
        model.addAttribute(NEW_VITAMIN_B_TWELVE_PROFILE_DTO, new NewVitaminBTwelveProfileDto());
        return "pages/dashboard/medical_file/vitamin_b_twelve/vitamin_b_twelve-add";
    }

    @PostMapping("/vitamin-b-twelve/add")
    @PreAuthorize("#username == authentication.name")
    public String newVitaminDProfile(Model model, @PathVariable String username,
                                     @Valid @ModelAttribute NewVitaminBTwelveProfileDto newVitaminBTwelveProfile) {
        vitaminBTwelve.addVitaminBTwelve(newVitaminBTwelveProfile, username);
        return "redirect:/" + username + "/medical-history/vitamin-b-twelve";
    }

    @DeleteMapping("/vitamin-b-twelve/{id}/delete")
    @PreAuthorize("#username == authentication.name")
    public String deleteVitaminDProfile(@PathVariable String username, @PathVariable Long id) {
        vitaminBTwelveRepository.deleteByIdAndMedicalFile_User_Username(id, username);
        return "redirect:/" + username + "/medical-history/vitamin-b-twelve";
    }
}
