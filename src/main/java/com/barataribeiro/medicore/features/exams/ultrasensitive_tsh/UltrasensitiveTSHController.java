package com.barataribeiro.medicore.features.exams.ultrasensitive_tsh;

import com.barataribeiro.medicore.features.exams.ultrasensitive_tsh.dtos.NewUltrasensitiveTSHProfileDto;
import com.barataribeiro.medicore.features.exams.ultrasensitive_tsh.dtos.UltrasensitiveTSHDto;
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
public class UltrasensitiveTSHController {
    private final UltrasensitiveTSHRepository ultrasensitiveTSHRepository;
    private final UltrasensitiveTSHService ultrasensitiveTSH;

    @GetMapping("/ultrasensitive-tsh")
    @PreAuthorize("#username == authentication.name")
    public String getUltrasensitiveTsh(Model model, @RequestParam(defaultValue = "0") int page,
                                       @RequestParam(defaultValue = "10") int perPage,
                                       @RequestParam(defaultValue = "DESC") String direction,
                                       @RequestParam(defaultValue = "reportDate") String orderBy,
                                       Authentication authentication, @PathVariable String username) {
        Page<UltrasensitiveTSHDto> response = ultrasensitiveTSH
                .getUltrasensitiveTshPaginated(page, perPage, direction, orderBy, authentication);

        BarChart chart = ultrasensitiveTSH.getUltrasensitiveTshChartInfo(response.getContent());

        model.addAttribute(PAGE_TITLE, "Ultrasensitive TSH");
        model.addAttribute(PAGE_DESCRIPTION, "Check your UltraSensitive TSH");
        model.addAttribute("uTSHs", response.getContent());
        model.addAttribute("currentPage", response.getNumber());
        model.addAttribute("totalPages", response.getTotalPages());
        model.addAttribute("totalItems", response.getTotalElements());
        model.addAttribute("uTSHChart", chart.toJson());
        return "pages/dashboard/medical_file/ultrasensitive_tsh/ultrasensitive_tsh";
    }

    @GetMapping("/ultrasensitive-tsh/add")
    @PreAuthorize("#username == authentication.name")
    public String newUltrasensitiveTSHProfile(Model model, @PathVariable String username) {
        model.addAttribute(PAGE_TITLE, "New Ultrasensitive TSH Profile");
        model.addAttribute(PAGE_DESCRIPTION, "Add a new Ultrasensitive TSH profile");
        model.addAttribute(NEW_ULTRASENSITIVE_TSH_PROFILE_DTO, new NewUltrasensitiveTSHProfileDto());
        return "pages/dashboard/medical_file/ultrasensitive_tsh/ultrasensitive_tsh-add";
    }

    @PostMapping("/ultrasensitive-tsh/add")
    @PreAuthorize("#username == authentication.name")
    public String newUltrasensitiveTSHProfile(Model model, @PathVariable String username,
                                              @Valid @ModelAttribute
                                              NewUltrasensitiveTSHProfileDto newUltrasensitiveTshProfileDto) {
        ultrasensitiveTSH.addUltrasensitiveTSH(newUltrasensitiveTshProfileDto, username);
        return "redirect:/" + username + "/medical-history/ultrasensitive-tsh";
    }

    @DeleteMapping("/ultrasensitive-tsh/{id}/delete")
    @PreAuthorize("#username == authentication.name")
    public String deleteUltrasensitiveTSHProfile(@PathVariable String username, @PathVariable Long id) {
        ultrasensitiveTSHRepository.deleteByIdAndMedicalFile_User_Username(id, username);
        return "redirect:/" + username + "/medical-history/ultrasensitive-tsh";
    }
}
