package com.barataribeiro.medicore.features.exams.urea_and_creatinine;

import com.barataribeiro.medicore.features.exams.urea_and_creatinine.dtos.NewUreaAndCreatinineDto;
import com.barataribeiro.medicore.features.exams.urea_and_creatinine.dtos.UreaAndCreatinineDto;
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
public class UreaAndCreatinineController {

    private final UreaAndCreatinineService ureaAndCreatinineService;
    private final UreaAndCreatinineRepository ureaAndCreatinineRepository;

    @GetMapping("/urea-and-creatinine")
    @PreAuthorize("#username == authentication.name")
    public String getUreaAndCreatinine(Model model, @RequestParam(defaultValue = "0") int page,
                                       @RequestParam(defaultValue = "10") int perPage,
                                       @RequestParam(defaultValue = "DESC") String direction,
                                       @RequestParam(defaultValue = "reportDate") String orderBy,
                                       Authentication authentication, @PathVariable String username) {
        Page<UreaAndCreatinineDto> response = ureaAndCreatinineService
                .getUreaAndCreatininePaginated(page, perPage, direction, orderBy, authentication);

        BarChart chart = ureaAndCreatinineService.getUreaAndCreatinineChartInfo(response.getContent());

        model.addAttribute(PAGE_TITLE, "Urea and Creatinine Profile");
        model.addAttribute(PAGE_DESCRIPTION, "Check your urea and creatinine profile");
        model.addAttribute("ureaAndCreatinines", response.getContent());
        model.addAttribute("currentPage", response.getNumber());
        model.addAttribute("totalPages", response.getTotalPages());
        model.addAttribute("totalItems", response.getTotalElements());
        model.addAttribute("ureaAndCreatinineChart", chart.toJson());
        return "pages/dashboard/medical_file/urea_and_creatinine/urea_and_creatinine";
    }

    @GetMapping("/urea-and-creatinine/add")
    @PreAuthorize("#username == authentication.name")
    public String newUricAcidProfile(Model model, @PathVariable String username) {
        model.addAttribute(PAGE_TITLE, "New Urea And Creatinine Profile");
        model.addAttribute(PAGE_DESCRIPTION, "Add a new Urea And Creatinine profile");
        model.addAttribute(NEW_UREA_AND_CREATININE_PROFILE_DTO, new NewUreaAndCreatinineDto());
        return "pages/dashboard/medical_file/urea_and_creatinine/urea_and_creatinine-add";
    }

    @PostMapping("/urea-and-creatinine/add")
    @PreAuthorize("#username == authentication.name")
    public String newUricAcidProfile(Model model, @PathVariable String username,
                                     @Valid @ModelAttribute NewUreaAndCreatinineDto newUreaAndCreatinineDto) {
        ureaAndCreatinineService.addUreaAndCreatinine(newUreaAndCreatinineDto, username);
        return "redirect:/" + username + "/medical-history/urea-and-creatinine";
    }

    @DeleteMapping("/urea-and-creatinine/{id}/delete")
    @PreAuthorize("#username == authentication.name")
    public String deleteUricAcidProfile(@PathVariable String username, @PathVariable Long id) {
        ureaAndCreatinineRepository.deleteByIdAndMedicalFile_User_Username(id, username);
        return "redirect:/" + username + "/medical-history/urea-and-creatinine";
    }
}
