package com.barataribeiro.medicore.features.exams.lipid_profile;

import com.barataribeiro.medicore.features.exams.lipid_profile.dtos.LipidProfileDto;
import com.barataribeiro.medicore.features.exams.lipid_profile.dtos.NewLipidProfileDto;
import com.barataribeiro.medicore.features.medicalfile.MedicalFile;
import com.barataribeiro.medicore.features.medicalfile.MedicalFileRepository;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.jetbrains.annotations.NotNull;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Sort;
import org.springframework.security.core.Authentication;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import software.xdev.chartjs.model.charts.LineChart;
import software.xdev.chartjs.model.data.LineData;
import software.xdev.chartjs.model.dataset.LineDataset;
import software.xdev.chartjs.model.options.LegendOptions;
import software.xdev.chartjs.model.options.LineOptions;
import software.xdev.chartjs.model.options.Plugins;

import java.util.Comparator;
import java.util.List;

@Service
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class LipidProfileService {


    private final LipidProfileRepository lipidProfileRepository;
    private final LipidProfileMapper lipidProfileMapper;
    private final MedicalFileRepository medicalFileRepository;

    @Transactional(readOnly = true)
    public Page<LipidProfileDto> getLipidProfilePaginated(int page, int perPage, @NotNull String direction,
                                                          String orderBy,
                                                          @NotNull Authentication authentication) {
        Sort.Direction sortDirection = direction.equalsIgnoreCase("DESC") ? Sort.Direction.DESC : Sort.Direction.ASC;
        orderBy = orderBy.equalsIgnoreCase("reportDate") ? "reportDate" : orderBy;
        PageRequest pageable = PageRequest.of(page, perPage, Sort.by(sortDirection, orderBy));

        Page<LipidProfile> lipidProfiles = lipidProfileRepository
                .findAllByMedicalFile_User_Username(authentication.getName(), pageable);

        return lipidProfiles.map(lipidProfileMapper::toLipidProfileDto);
    }

    @Transactional
    public void addLipidProfile(@Valid @NotNull NewLipidProfileDto newLipidProfileDto, String username, Model model,
                                @NotNull BindingResult bindingResult) {
        MedicalFile medicalFile = medicalFileRepository
                .findByUser_Username(username).orElseThrow(() -> new RuntimeException("Medical file not found"));

        LipidProfile newLipidProfile = LipidProfile.builder()
                                                   .totalCholesterol(newLipidProfileDto.getTotalCholesterol())
                                                   .hdlCholesterol(newLipidProfileDto.getHdlCholesterol())
                                                   .ldlCholesterol(newLipidProfileDto.getLdlCholesterol())
                                                   .vldlCholesterol(newLipidProfileDto.getVldlCholesterol())
                                                   .triglycerides(newLipidProfileDto.getTriglycerides())
                                                   .reportDate(newLipidProfileDto.getReportDate())
                                                   .medicalFile(medicalFile)
                                                   .build();

        lipidProfileRepository.save(newLipidProfile);
    }


    public LineChart getLipidProfileChartInfo(@NotNull List<LipidProfileDto> data) {
        List<LipidProfileDto> sortedData = data.parallelStream()
                                               .sorted(Comparator.comparing(LipidProfileDto::getReportDate))
                                               .toList();

        String[] dateLabels = sortedData.parallelStream().map(profile -> profile.getReportDate().toString())
                                        .toArray(String[]::new);

        final LineData lineData = new LineData();
        lineData.addLabels(dateLabels);
        lineData.addDataset(new LineDataset().setLabel("Total Cholesterol").setData(getTotalCholesterol(sortedData)));
        lineData.addDataset(new LineDataset().setLabel("HDL Cholesterol").setData(getHdlCholesterol(sortedData)));
        lineData.addDataset(new LineDataset().setLabel("LDL Cholesterol").setData(getLdlCholesterol(sortedData)));
        lineData.addDataset(new LineDataset().setLabel("VLDL Cholesterol").setData(getVldlCholesterol(sortedData)));
        lineData.addDataset(new LineDataset().setLabel("Triglycerides").setData(getTriglycerides(sortedData)));

        final Plugins legendPlugin = new Plugins().setLegend(new LegendOptions().setPosition("bottom"));

        return new LineChart().setData(lineData).setOptions(new LineOptions().setPlugins(legendPlugin)
                                                                             .setResponsive(true)
                                                                             .setMaintainAspectRatio(false));
    }

    private Double @NotNull [] getTriglycerides(@NotNull List<LipidProfileDto> data) {
        return data.parallelStream()
                   .map(LipidProfileDto::getTriglycerides)
                   .toArray(Double[]::new);
    }

    private Double @NotNull [] getVldlCholesterol(@NotNull List<LipidProfileDto> data) {
        return data.parallelStream()
                   .map(LipidProfileDto::getVldlCholesterol)
                   .toArray(Double[]::new);
    }

    private Double @NotNull [] getLdlCholesterol(@NotNull List<LipidProfileDto> data) {
        return data.parallelStream()
                   .map(LipidProfileDto::getLdlCholesterol)
                   .toArray(Double[]::new);
    }

    private Double @NotNull [] getHdlCholesterol(@NotNull List<LipidProfileDto> data) {
        return data.parallelStream()
                   .map(LipidProfileDto::getHdlCholesterol)
                   .toArray(Double[]::new);
    }

    private Double @NotNull [] getTotalCholesterol(@NotNull List<LipidProfileDto> data) {
        return data.parallelStream()
                   .map(LipidProfileDto::getTotalCholesterol)
                   .toArray(Double[]::new);
    }


}
