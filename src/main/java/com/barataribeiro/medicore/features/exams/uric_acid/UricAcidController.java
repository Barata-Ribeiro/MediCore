package com.barataribeiro.medicore.features.exams.uric_acid;

import com.barataribeiro.medicore.features.exams.uric_acid.dtos.NewUricAcidProfileDto;
import com.barataribeiro.medicore.features.exams.uric_acid.dtos.UricAcidDto;
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
public class UricAcidController {

    private final UricAcidRepository uricAcidRepository;
    private final UricAcidService uricAcidService;

    @GetMapping("/uric-acid")
    @PreAuthorize("#username == authentication.name")
    public String getUricAcid(Model model, @RequestParam(defaultValue = "0") int page,
                              @RequestParam(defaultValue = "10") int perPage,
                              @RequestParam(defaultValue = "DESC") String direction,
                              @RequestParam(defaultValue = "reportDate") String orderBy,
                              Authentication authentication, @PathVariable String username) {
        Page<UricAcidDto> response = uricAcidService.getUricAcidPaginated(page, perPage, direction, orderBy,
                                                                          authentication);
        BarChart chart = uricAcidService.getUricAcidChartInfo(response.getContent());

        model.addAttribute(PAGE_TITLE, "Uric Acid Profile");
        model.addAttribute(PAGE_DESCRIPTION, "Check your uric acid profile");
        model.addAttribute("uricAcids", response.getContent());
        model.addAttribute("currentPage", response.getNumber());
        model.addAttribute("totalPages", response.getTotalPages());
        model.addAttribute("totalItems", response.getTotalElements());
        model.addAttribute("uricAcidChart", chart.toJson());
        return "pages/dashboard/medical_file/uric_acid/uric_acid";
    }

    @GetMapping("/uric-acid/add")
    @PreAuthorize("#username == authentication.name")
    public String newUricAcidProfile(Model model, @PathVariable String username) {
        model.addAttribute(PAGE_TITLE, "New Vitamin D3 Profile");
        model.addAttribute(PAGE_DESCRIPTION, "Add a new Vitamin D3 profile");
        model.addAttribute(NEW_URIC_ACID_PROFILE_DTO, new NewUricAcidProfileDto());
        return "pages/dashboard/medical_file/uric_acid/uric_acid-add";
    }

    @PostMapping("/uric-acid/add")
    @PreAuthorize("#username == authentication.name")
    public String newUricAcidProfile(Model model, @PathVariable String username,
                                     @Valid @ModelAttribute NewUricAcidProfileDto newUricAcidProfileDto) {
        uricAcidService.addUricAcid(newUricAcidProfileDto, username);
        return "redirect:/" + username + "/medical-history/uric-acid";
    }

    @DeleteMapping("/uric-acid/{id}/delete")
    @PreAuthorize("#username == authentication.name")
    public String deleteUricAcidProfile(@PathVariable String username, @PathVariable Long id) {
        uricAcidRepository.deleteByIdAndMedicalFile_User_Username(id, username);
        return "redirect:/" + username + "/medical-history/uric-acid";
    }
}
