package com.barataribeiro.medicore.features.user;

import com.barataribeiro.medicore.features.user.dtos.DashboardDto;
import com.barataribeiro.medicore.features.user.dtos.UpdateAppUserDto;
import jakarta.servlet.http.HttpSession;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.jetbrains.annotations.NotNull;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.session.Session;
import org.springframework.session.data.redis.RedisIndexedSessionRepository;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;
import software.xdev.chartjs.model.charts.PieChart;

import java.security.Principal;
import java.util.Collection;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

import static com.barataribeiro.medicore.utils.ApplicationConstants.*;

@Slf4j
@Controller
@RequestMapping("/{username}")
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class AppUserController {
    private final UserMapper userMapper;
    private final ProfileRepository profileRepository;
    private final AppUserService appUserService;
    private final RedisIndexedSessionRepository sessionRepository;

    @GetMapping
    @PreAuthorize("#username == authentication.name")
    public String dashboard(@PathVariable String username, Model model) {
        DashboardDto dashboardInformation = appUserService.getDashboardInformation(username);
        PieChart dashboardChart = appUserService.getDashboardPieChart(dashboardInformation);

        model.addAttribute(PAGE_TITLE, username);
        model.addAttribute(PAGE_DESCRIPTION,
                           "Welcome to your dashboard, %s! Here you can manage your profile, settings, and more."
                                   .formatted(username));
        model.addAttribute("dashboard", dashboardInformation);
        model.addAttribute("dashboardChart", dashboardChart.toJson());
        return "pages/dashboard/index";
    }

    @GetMapping("/profile")
    @PreAuthorize("#username == authentication.name")
    public String profile(@PathVariable String username, Model model) {
        Profile userProfile = profileRepository.findByUser_Username(username)
                                               .orElseThrow(() -> new RuntimeException("User not found"));

        model.addAttribute(PAGE_TITLE, username);
        model.addAttribute("profile", userMapper.toUserProfileDto(userProfile));
        model.addAttribute(UPDATE_APP_USER_DTO, new UpdateAppUserDto());
        model.addAttribute("success", false);
        return "pages/dashboard/profile/profile";
    }

    @PostMapping("/profile")
    @PreAuthorize("#username == authentication.name")
    public String updateProfile(@PathVariable String username, Model model,
                                @Valid @ModelAttribute UpdateAppUserDto updateAppUserDto, BindingResult bindingResult) {

        String response = appUserService.updateAppUser(username, model, updateAppUserDto, bindingResult);
        if (response != null) return response;

        model.addAttribute(PAGE_TITLE, username);
        model.addAttribute(UPDATE_APP_USER_DTO, new UpdateAppUserDto());
        model.addAttribute("success", true);

        return "pages/dashboard/profile/profile";
    }

    @GetMapping("/settings")
    @PreAuthorize("#username == authentication.name")
    public String settings(@PathVariable String username, Model model, Principal principal, HttpSession httpSession) {
        Collection<? extends Session> redisSessions = appUserService.getSessions(principal);
        RedisIndexedSessionRepository.RedisSession actualRedisSession =
                sessionRepository.findById(httpSession.getId());

        List<Map<String, Object>> sessions = redisSessions.stream().map(session -> {
            Map<String, Object> sessionMap = new LinkedHashMap<>();
            populateSessionDetails(sessionMap, (RedisIndexedSessionRepository.RedisSession) session);
            return sessionMap;
        }).toList();

        LinkedHashMap<String, Object> actualSession = new LinkedHashMap<>();
        populateSessionDetails(actualSession, actualRedisSession);

        model.addAttribute(PAGE_TITLE, "Settings");
        model.addAttribute("sessions", sessions);
        model.addAttribute("actualSession", actualSession);
        return "pages/dashboard/profile/settings";
    }

    private void populateSessionDetails(@NotNull Map<String, Object> map,
                                        RedisIndexedSessionRepository.@NotNull RedisSession session) {
        map.put("id", session.getId());
        map.put("security_context", session.getAttribute("SPRING_SECURITY_CONTEXT"));

        Object sessionMetadata = session.getAttribute("SESSION_METADATA");
        if (sessionMetadata == null) {
            Map<String, Object> defaultMetadata = new LinkedHashMap<>();
            defaultMetadata.put("userAgent", "Unknown");
            defaultMetadata.put("ipAddress", "Unknown");
            defaultMetadata.put("loginTime", System.currentTimeMillis());
            defaultMetadata.put("authenticationType", "Remember-Me (Restored)");
            sessionMetadata = defaultMetadata;
        }

        map.put("session_metadata", sessionMetadata);
        map.put("creationTime", session.getCreationTime());
        map.put("lastAccessedTime", session.getLastAccessedTime());
        map.put("isExpired", session.isExpired());
        map.put("maxInactiveInterval", session.getMaxInactiveInterval().getSeconds());
    }
}
