package com.barataribeiro.medicore.features.exams.complete_blood_count;

import com.barataribeiro.medicore.features.exams.complete_blood_count.dtos.CompleteBloodCountDto;
import com.barataribeiro.medicore.features.exams.complete_blood_count.dtos.NewCBCDto;
import lombok.AllArgsConstructor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.security.core.Authentication;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import software.xdev.chartjs.model.charts.LineChart;

import static com.barataribeiro.medicore.utils.ApplicationConstants.*;

@Controller
@RequestMapping("/{username}/medical-history")
@AllArgsConstructor(onConstructor_ = {@Autowired})
public class CompleteBloodCountController {
    private final CompleteBloodCountService completeBloodCountService;
    private final CompleteBloodCountRepository completeBloodCountRepository;

    @GetMapping("/blood-count")
    @PreAuthorize("#username == authentication.name")
    public String getCompleteBloodCount(Model model, @RequestParam(defaultValue = "0") int page,
                                        @RequestParam(defaultValue = "10") int perPage,
                                        @RequestParam(defaultValue = "DESC") String direction,
                                        @RequestParam(defaultValue = "reportDate") String orderBy,
                                        Authentication authentication, @PathVariable String username) {
        Page<CompleteBloodCountDto> response = completeBloodCountService
                .getCompleteBloodCountPaginated(page, perPage, direction, orderBy, authentication);

        LineChart chart = completeBloodCountService.getCompleteBloodCountChart(response.getContent());

        model.addAttribute(PAGE_TITLE, "Complete Blood Count");
        model.addAttribute(PAGE_DESCRIPTION, "Check your complete blood count");
        model.addAttribute("bloodCount", response.getContent());
        model.addAttribute("currentPage", response.getNumber());
        model.addAttribute("totalPages", response.getTotalPages());
        model.addAttribute("totalItems", response.getTotalElements());
        model.addAttribute("bloodCountChart", chart.toJson());
        return "pages/dashboard/medical_file/cbc_count/blood-count";
    }

    @GetMapping("/blood-count/add")
    @PreAuthorize("#username == authentication.name")
    public String newCompleteBloodCount(Model model, @PathVariable String username) {
        model.addAttribute(PAGE_TITLE, "New CBC Test");
        model.addAttribute(PAGE_DESCRIPTION, "Add a new complete blood count test to your medical history");
        model.addAttribute(NEW_CBC_DTO, new NewCBCDto());
        return "pages/dashboard/medical_file/cbc_count/blood-count-add";
    }

    @DeleteMapping("/blood-count/{id}/delete")
    @PreAuthorize("#username == authentication.name")
    public String deleteLipidProfile(@PathVariable String username, @PathVariable Long id) {
        completeBloodCountRepository.deleteByIdAndMedicalFile_User_Username(id, username);
        return "redirect:/" + username + "/medical-history/blood-count";
    }
}
