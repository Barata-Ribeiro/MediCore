package com.barataribeiro.medicore.features.exams.glucose;

import com.barataribeiro.medicore.features.exams.glucose.dtos.GlucoseDto;
import com.barataribeiro.medicore.features.exams.glucose.dtos.NewGlucoseDto;
import com.barataribeiro.medicore.features.medical_file.MedicalFile;
import com.barataribeiro.medicore.features.medical_file.MedicalFileRepository;
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
import software.xdev.chartjs.model.charts.BarChart;
import software.xdev.chartjs.model.data.BarData;
import software.xdev.chartjs.model.dataset.BarDataset;
import software.xdev.chartjs.model.options.BarOptions;
import software.xdev.chartjs.model.options.LegendOptions;
import software.xdev.chartjs.model.options.Plugins;
import software.xdev.chartjs.model.options.scale.Scales;
import software.xdev.chartjs.model.options.scale.cartesian.CartesianScaleOptions;

import java.util.Comparator;
import java.util.List;

@Service
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class GlucoseService {

    private final GlucoseRepository glucoseRepository;
    private final GlucoseMapper glucoseMapper;
    private final MedicalFileRepository medicalFileRepository;

    @Transactional(readOnly = true)
    public Page<GlucoseDto> getGlucosePaginated(int page, int perPage, @NotNull String direction,
                                                String orderBy,
                                                @NotNull Authentication authentication) {
        Sort.Direction sortDirection = direction.equalsIgnoreCase("DESC") ? Sort.Direction.DESC : Sort.Direction.ASC;
        orderBy = orderBy.equalsIgnoreCase("reportDate") ? "reportDate" : orderBy;
        PageRequest pageable = PageRequest.of(page, perPage, Sort.by(sortDirection, orderBy));

        Page<Glucose> glucose = glucoseRepository
                .findAllByMedicalFile_User_Username(authentication.getName(), pageable);

        return glucose.map(glucoseMapper::toGlucoseDto);
    }

    public BarChart getGlucoseChart(@NotNull List<GlucoseDto> data) {
        List<GlucoseDto> sortedData = data.parallelStream()
                                          .sorted(Comparator.comparing(GlucoseDto::getReportDate))
                                          .toList();

        String[] dataLabels = sortedData.parallelStream().map(profile -> profile.getReportDate().toString())
                                        .toArray(String[]::new);

        final BarData barData = new BarData();
        barData.setLabels(dataLabels);
        barData.addDataset(new BarDataset()
                                   .setLabel("Glucose")
                                   .setData(getGlucoseLevel(sortedData))
                                   .setStack("stack 0")
                                   .setBackgroundColor("rgba(255, 99, 132, 1)"));
        barData.addDataset(new BarDataset()
                                   .setLabel("Glycated Hemoglobin")
                                   .setData(getGlycatedHemoglobin(sortedData))
                                   .setStack("stack 1").setBackgroundColor("rgba(54, 162, 235, 1)"));
        barData.addDataset(new BarDataset().setLabel("Estimated Average Glucose")
                                           .setData(getEstimatedAvgGlucose(sortedData))
                                           .setStack("stack 1")
                                           .setBackgroundColor("rgba(255, 206, 86, 1)"));

        final Plugins legendPlugin = new Plugins().setLegend(new LegendOptions().setPosition("bottom"));

        return new BarChart().setData(barData)
                             .setOptions(new BarOptions()
                                                 .setScales(new Scales().addScale(Scales.ScaleAxis.X,
                                                                                  new CartesianScaleOptions())
                                                                        .addScale(Scales.ScaleAxis.Y,
                                                                                  new CartesianScaleOptions()))
                                                 .setPlugins(legendPlugin)
                                                 .setResponsive(true)
                                                 .setMaintainAspectRatio(false));
    }

    @Transactional
    public void addGlucoseProfile(@Valid @NotNull NewGlucoseDto newGlucoseDto, String username) {
        MedicalFile medicalFile = medicalFileRepository
                .findByUser_Username(username).orElseThrow(() -> new RuntimeException("Medical file not found"));

        Glucose newGlucoseLevelProfile = Glucose.builder()
                                                .glucoseLevel(newGlucoseDto.getGlucoseLevel())
                                                .glycatedHemoglobin(newGlucoseDto.getGlycatedHemoglobin())
                                                .estimatedAverageGlucose(newGlucoseDto.getEstimatedAverageGlucose())
                                                .reportDate(newGlucoseDto.getReportDate())
                                                .medicalFile(medicalFile)
                                                .build();

        glucoseRepository.save(newGlucoseLevelProfile);
    }

    private Double @NotNull [] getEstimatedAvgGlucose(@NotNull List<GlucoseDto> sortedData) {
        return sortedData.parallelStream()
                         .map(GlucoseDto::getEstimatedAverageGlucose)
                         .toArray(Double[]::new);
    }

    private Double @NotNull [] getGlycatedHemoglobin(@NotNull List<GlucoseDto> sortedData) {
        return sortedData.parallelStream()
                         .map(GlucoseDto::getGlycatedHemoglobin)
                         .toArray(Double[]::new);
    }

    private Double @NotNull [] getGlucoseLevel(@NotNull List<GlucoseDto> sortedData) {
        return sortedData.parallelStream()
                         .map(GlucoseDto::getGlucoseLevel)
                         .toArray(Double[]::new);
    }
}
