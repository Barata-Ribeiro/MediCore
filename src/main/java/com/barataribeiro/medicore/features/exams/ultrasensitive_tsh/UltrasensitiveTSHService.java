package com.barataribeiro.medicore.features.exams.ultrasensitive_tsh;

import com.barataribeiro.medicore.features.exams.ultrasensitive_tsh.dtos.NewUltrasensitiveTSHProfileDto;
import com.barataribeiro.medicore.features.exams.ultrasensitive_tsh.dtos.UltrasensitiveTSHDto;
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

import java.util.Comparator;
import java.util.List;

@Service
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class UltrasensitiveTSHService {
    private final UltrasensitiveTSHRepository ultrasensitiveTSHRepository;
    private final UltrasensitiveTSHMapper ultrasensitiveTSHMapper;
    private final MedicalFileRepository medicalFileRepository;

    @Transactional(readOnly = true)
    public Page<UltrasensitiveTSHDto> getUltrasensitiveTshPaginated(int page, int perPage, @NotNull String direction,
                                                                    String orderBy,
                                                                    @NotNull Authentication authentication) {
        Sort.Direction sortDirection = direction.equalsIgnoreCase("DESC") ? Sort.Direction.DESC : Sort.Direction.ASC;
        orderBy = orderBy.equalsIgnoreCase("reportDate") ? "reportDate" : orderBy;
        PageRequest pageable = PageRequest.of(page, perPage, Sort.by(sortDirection, orderBy));

        Page<UltrasensitiveTSH> ultrasensitiveTSHPage = ultrasensitiveTSHRepository
                .findAllByMedicalFile_User_Username(authentication.getName(), pageable);

        return ultrasensitiveTSHPage.map(ultrasensitiveTSHMapper::toUltrasensitiveTSHDto);
    }

    @Transactional
    public void addUltrasensitiveTSH(@Valid @NotNull NewUltrasensitiveTSHProfileDto newUltrasensitiveTshProfileDto,
                                     String username) {
        MedicalFile medicalFile = medicalFileRepository
                .findByUser_Username(username).orElseThrow(() -> new RuntimeException("Medical file not found"));

        UltrasensitiveTSH newUltrasensitiveTshProfile = UltrasensitiveTSH.builder()
                                                                         .ultrasensitiveTSHLevel(
                                                                                 newUltrasensitiveTshProfileDto
                                                                                         .getUltrasensitiveTSHLevel())
                                                                         .reportDate(newUltrasensitiveTshProfileDto
                                                                                             .getReportDate())
                                                                         .medicalFile(medicalFile)
                                                                         .build();

        ultrasensitiveTSHRepository.save(newUltrasensitiveTshProfile);
    }

    public BarChart getUltrasensitiveTshChartInfo(@NotNull List<UltrasensitiveTSHDto> data) {
        List<UltrasensitiveTSHDto> sortedData = data.parallelStream()
                                                    .sorted(Comparator.comparing(UltrasensitiveTSHDto::getReportDate))
                                                    .toList();

        String[] dateLabels = sortedData.stream().map(UltrasensitiveTSHDto::getReportDate).map(Object::toString)
                                        .toArray(String[]::new);

        final BarData barData = new BarData();
        barData.setLabels(dateLabels);
        barData.addDataset(new BarDataset().setLabel("Ultrasensitive TSH")
                                           .setData(getUltrasensitiveTSH(data))
                                           .setBackgroundColor("rgba(65, 4, 69, 1)"));

        final Plugins legendPlugin = new Plugins().setLegend(new LegendOptions().setPosition("bottom"));

        return new BarChart().setData(barData).setOptions(new BarOptions().setPlugins(legendPlugin)
                                                                          .setResponsive(true)
                                                                          .setMaintainAspectRatio(false));
    }

    private Double @NotNull [] getUltrasensitiveTSH(@NotNull List<UltrasensitiveTSHDto> data) {
        return data.parallelStream()
                   .map(UltrasensitiveTSHDto::getUltrasensitiveTSHLevel)
                   .toArray(Double[]::new);
    }
}
