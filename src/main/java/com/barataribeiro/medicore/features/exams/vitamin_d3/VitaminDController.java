package com.barataribeiro.medicore.features.exams.vitamin_d3;

import com.barataribeiro.medicore.features.exams.vitamin_d3.dtos.VitaminDDto;
import lombok.AllArgsConstructor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.security.core.Authentication;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import software.xdev.chartjs.model.charts.BarChart;

import static com.barataribeiro.medicore.utils.ApplicationConstants.PAGE_DESCRIPTION;
import static com.barataribeiro.medicore.utils.ApplicationConstants.PAGE_TITLE;

@Controller
@RequestMapping("/{username}/medical-history")
@AllArgsConstructor(onConstructor_ = {@Autowired})
public class VitaminDController {
    private final VitaminDService vitaminDService;

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
}
