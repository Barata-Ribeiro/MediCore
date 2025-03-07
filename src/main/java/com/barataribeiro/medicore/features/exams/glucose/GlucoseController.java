package com.barataribeiro.medicore.features.exams.glucose;

import lombok.RequiredArgsConstructor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.security.core.Authentication;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;

import static com.barataribeiro.medicore.utils.ApplicationConstants.PAGE_DESCRIPTION;
import static com.barataribeiro.medicore.utils.ApplicationConstants.PAGE_TITLE;

@Controller
@RequestMapping("/{username}/medical-history")
@RequiredArgsConstructor(onConstructor = @__(@Autowired))
class GlucoseController {

    @GetMapping("/glucose")
    @PreAuthorize("#username == authentication.name")
    String getGlucose(Model model, @RequestParam(defaultValue = "0") int page,
                      @RequestParam(defaultValue = "10") int perPage,
                      @RequestParam(defaultValue = "DESC") String direction,
                      @RequestParam(defaultValue = "reportDate") String orderBy,
                      Authentication authentication, @PathVariable String username) {

        model.addAttribute(PAGE_TITLE, "Glucose");
        model.addAttribute(PAGE_DESCRIPTION, "Check your glucose levels");
        return "";
    }
}
