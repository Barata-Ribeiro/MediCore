package com.barataribeiro.medicore.features.user;

import com.barataribeiro.medicore.features.medical_file.MedicalFileMapper;
import com.barataribeiro.medicore.features.medical_file.MedicalFileRepository;
import com.barataribeiro.medicore.features.user.dtos.DashboardDao;
import com.barataribeiro.medicore.features.user.dtos.DashboardDto;
import com.barataribeiro.medicore.features.user.dtos.UpdateAppUserDto;
import jakarta.annotation.Nullable;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.jetbrains.annotations.NotNull;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.session.FindByIndexNameSessionRepository;
import org.springframework.session.Session;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.validation.FieldError;
import software.xdev.chartjs.model.charts.PieChart;
import software.xdev.chartjs.model.data.PieData;
import software.xdev.chartjs.model.dataset.PieDataset;
import software.xdev.chartjs.model.options.LegendOptions;
import software.xdev.chartjs.model.options.PieOptions;
import software.xdev.chartjs.model.options.Plugins;
import software.xdev.chartjs.model.options.Title;

import java.security.Principal;
import java.util.Collection;
import java.util.Date;
import java.util.Set;
import java.util.stream.Stream;

import static com.barataribeiro.medicore.utils.ApplicationConstants.PAGE_TITLE;
import static com.barataribeiro.medicore.utils.ApplicationConstants.UPDATE_APP_USER_DTO;

@Slf4j
@Service
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class AppUserService {
    private final ProfileRepository profileRepository;
    private final PasswordEncoder passwordEncoder;
    private final AppUserRepository appUserRepository;
    private final UserMapper userMapper;
    private final FindByIndexNameSessionRepository<? extends Session> sessions;
    private final MedicalFileRepository medicalFileRepository;
    private final MedicalFileMapper medicalFileMapper;

    public Collection<? extends Session> getSessions(@NotNull Principal principal) {
        return sessions.findByPrincipalName(principal.getName()).values();
    }

    public void removeSession(@NotNull Principal principal, String sessionIdToDelete) {
        Set<String> usersSessionIds = sessions.findByPrincipalName(principal.getName()).keySet();
        if (usersSessionIds.contains(sessionIdToDelete)) sessions.deleteById(sessionIdToDelete);
    }

    @Transactional
    public @Nullable String updateAppUser(String username, Model model, @NotNull UpdateAppUserDto body,
                                          BindingResult bindingResult) {
        Profile profile = profileRepository.findByUser_Username(username)
                                           .orElseThrow(() -> new RuntimeException("Profile not found"));
        AppUser user = profile.getUser();

        boolean passwordMatches = passwordEncoder.matches(body.getCurrentPassword(), user.getPassword());
        if (!passwordMatches) bindingResult.addError(new FieldError(UPDATE_APP_USER_DTO, "currentPassword",
                                                                    "You provided an incorrect password"));

        verifyIfBodyExistsThenUpdateProperties(body, bindingResult, user, profile);

        if (bindingResult.hasErrors()) {
            model.addAttribute(PAGE_TITLE, username);
            model.addAttribute("profile", profile);
            model.addAttribute(UPDATE_APP_USER_DTO, body);
            model.addAttribute("success", false);
            return "pages/dashboard/profile/profile";
        }

        appUserRepository.save(user);

        model.addAttribute("profile", userMapper.toUserProfileDto(profileRepository.saveAndFlush(profile)));

        return null;
    }

    @Transactional(readOnly = true)
    public DashboardDto getDashboardInformation(String username) {
        DashboardDao dashboardData = profileRepository
                .getDashboardDataRaw(username)
                .orElseThrow(() -> new RuntimeException("Dashboard data not found"));

        final long sumOfExams = Stream.of(dashboardData.getTotalLipidProfiles(),
                                          dashboardData.getTotalCompleteBloodCounts(),
                                          dashboardData.getTotalGlucoses(),
                                          dashboardData.getTotalVitaminDs(),
                                          dashboardData.getTotalVitaminB12s(),
                                          dashboardData.getTotalUreaAndCreatinines(),
                                          dashboardData.getTotalUricAcids()).parallel().reduce(0L, Long::sum);

        return DashboardDto.builder().profile(userMapper.toUserProfileDto(dashboardData.getProfile()))
                           .medicalFile(medicalFileMapper.toMedicalFileDto(dashboardData.getMedicalFile()))
                           .totalLipidProfiles(dashboardData.getTotalLipidProfiles())
                           .totalCompleteBloodCounts(dashboardData.getTotalCompleteBloodCounts())
                           .totalGlucoses(dashboardData.getTotalGlucoses())
                           .totalVitaminDs(dashboardData.getTotalVitaminDs())
                           .totalVitaminB12s(dashboardData.getTotalVitaminB12s())
                           .totalUreaAndCreatinines(dashboardData.getTotalUreaAndCreatinines())
                           .totalUricAcids(dashboardData.getTotalUricAcids())
                           .totalMedicalExams(sumOfExams)
                           .build();
    }

    public PieChart getDashboardPieChart(@NotNull DashboardDto dashboardDto) {
        String[] labels = {"Lipid Profile", "Complete Blood Count", "Glucose", "Vitamin D", "Vitamin B12",
                           "Urea and Creatinine", "Uric Acid"};

        final PieData pieData = new PieData();
        final PieDataset medicalExamsDataset = new PieDataset().setLabel("Medical Exams Count")
                                                               .addData(dashboardDto.getTotalLipidProfiles())
                                                               .addData(dashboardDto.getTotalCompleteBloodCounts())
                                                               .addData(dashboardDto.getTotalGlucoses())
                                                               .addData(dashboardDto.getTotalVitaminDs())
                                                               .addData(dashboardDto.getTotalVitaminB12s())
                                                               .addData(dashboardDto.getTotalUreaAndCreatinines())
                                                               .addData(dashboardDto.getTotalUricAcids());
        pieData.addLabels(labels);
        pieData.addDataset(medicalExamsDataset.addBackgroundColors("rgba(255, 99, 132, 0.2)",
                                                                   "rgba(54, 162, 235, 0.2)",
                                                                   "rgba(255, 206, 86, 0.2)",
                                                                   "rgba(75, 192, 192, 0.2)",
                                                                   "rgba(153, 102, 255, 0.2)",
                                                                   "rgba(255, 159, 64, 0.2)",
                                                                   "rgba(255, 99, 132, 0.2)"));

        final Plugins charTitle = new Plugins().setTitle(new Title().setDisplay(true)
                                                                    .setText("Medical Exams").setPosition("top"));
        final Plugins legendPosition = new Plugins().setLegend(new LegendOptions().setPosition("top"));

        return new PieChart().setData(pieData).setOptions(new PieOptions()
                                                                  .setPlugins(charTitle)
                                                                  .setPlugins(legendPosition)
                                                                  .setMaintainAspectRatio(false)
                                                                  .setResponsive(true));
    }


    private void verifyIfBodyExistsThenUpdateProperties(@NotNull UpdateAppUserDto body, BindingResult bindingResult,
                                                        @NotNull AppUser user, @NotNull Profile profile) {
        updateDisplayName(body.getDisplayName(), bindingResult, user);
        updateEmail(body.getEmail(), bindingResult, user);
        updatePassword(body.getNewPassword(), body.getPasswordConfirmation(), bindingResult, user);
        updateFirstName(body.getFirstName(), bindingResult, profile);
        updateLastName(body.getLastName(), bindingResult, profile);
        updateBirthDate(body.getBirthDate(), bindingResult, profile);
        updateAvatarUrl(body.getAvatarUrl(), bindingResult, user);
        updateSex(body.getSex(), bindingResult, profile);
        updateTitle(body.getTitle(), bindingResult, profile);
        updateBiography(body.getBiography(), bindingResult, profile);
    }

    private void updateDisplayName(String displayName, BindingResult bindingResult, AppUser user) {
        if (displayName != null && !bindingResult.hasErrors()) user.setDisplayName(displayName);
    }

    private void updateEmail(String email, BindingResult bindingResult, AppUser user) {
        if (email != null) {
            if (email.equals(user.getEmail())) {
                bindingResult.addError(new FieldError(UPDATE_APP_USER_DTO, "email", "You already use this email."));
            } else if (appUserRepository.existsByEmail(email)) {
                bindingResult.addError(new FieldError(UPDATE_APP_USER_DTO, "email", "Email already in use."));
            }

            if (!bindingResult.hasErrors()) user.setEmail(email);
        }
    }

    private void updatePassword(String newPassword, String confirmation, BindingResult bindingResult, AppUser user) {
        if (newPassword != null) {
            if (confirmation == null || confirmation.isBlank()) {
                bindingResult.addError(new FieldError(UPDATE_APP_USER_DTO, "passwordConfirmation",
                                                      "You must confirm your new password."));
            } else if (!newPassword.equals(confirmation)) {
                bindingResult.addError(new FieldError(UPDATE_APP_USER_DTO, "passwordConfirmation",
                                                      "The passwords do not match."));
            }

            if (!bindingResult.hasErrors()) user.setPassword(passwordEncoder.encode(newPassword));
        }
    }

    private void updateFirstName(String firstName, BindingResult bindingResult, Profile profile) {
        if (firstName != null && !bindingResult.hasErrors()) profile.setFirstName(firstName);
    }

    private void updateLastName(String lastName, BindingResult bindingResult, Profile profile) {
        if (lastName != null && !bindingResult.hasErrors()) profile.setLastName(lastName);
    }

    private void updateBirthDate(Date birthDate, BindingResult bindingResult, Profile profile) {
        if (birthDate != null && !bindingResult.hasErrors()) profile.setBirthDate(birthDate);
    }

    private void updateAvatarUrl(String avatarUrl, BindingResult bindingResult, AppUser user) {
        if (avatarUrl != null && !bindingResult.hasErrors()) user.setAvatarUrl(avatarUrl);
    }

    private void updateSex(String sex, BindingResult bindingResult, Profile profile) {
        if (sex != null && !bindingResult.hasErrors()) profile.setSex(sex);
    }

    private void updateTitle(String title, BindingResult bindingResult, Profile profile) {
        if (title != null && !bindingResult.hasErrors()) profile.setTitle(title);
    }

    private void updateBiography(String biography, BindingResult bindingResult, Profile profile) {
        if (biography != null && !bindingResult.hasErrors()) profile.setBiography(biography);
    }
}
