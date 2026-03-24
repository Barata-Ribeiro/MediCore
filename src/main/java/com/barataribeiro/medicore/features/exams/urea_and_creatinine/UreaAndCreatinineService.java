package com.barataribeiro.medicore.features.exams.urea_and_creatinine;

import com.barataribeiro.medicore.features.exams.urea_and_creatinine.dtos.NewUreaAndCreatinineDto;
import com.barataribeiro.medicore.features.exams.urea_and_creatinine.dtos.UreaAndCreatinineDto;
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
public class UreaAndCreatinineService {
    private final UreaAndCreatinineRepository ureaAndCreatinineRepository;
    private final UreaAndCreatinineMapper ureaAndCreatinineMapper;
    private final MedicalFileRepository medicalFileRepository;

    @Transactional(readOnly = true)
    public Page<UreaAndCreatinineDto> getUreaAndCreatininePaginated(int page, int perPage, @NotNull String direction,
                                                                    String orderBy,
                                                                    @NotNull Authentication authentication) {
        Sort.Direction sortDirection = direction.equalsIgnoreCase("DESC") ? Sort.Direction.DESC : Sort.Direction.ASC;
        orderBy = orderBy.equalsIgnoreCase("reportDate") ? "reportDate" : orderBy;
        PageRequest pageable = PageRequest.of(page, perPage, Sort.by(sortDirection, orderBy));

        Page<UreaAndCreatinine> ureaAndCreatininePage = ureaAndCreatinineRepository
                .findAllByMedicalFile_User_Username(authentication.getName(), pageable);

        return ureaAndCreatininePage.map(ureaAndCreatinineMapper::toUreaAndCreatinineDto);
    }

    @Transactional
    public void addUreaAndCreatinine(@Valid @NotNull NewUreaAndCreatinineDto newUreaAndCreatinineDto, String username) {
        MedicalFile medicalFile = medicalFileRepository
                .findByUser_Username(username).orElseThrow(() -> new RuntimeException("Medical file not found"));

        UreaAndCreatinine newUreaAndCreatinine = UreaAndCreatinine.builder()
                                                                  .urea(newUreaAndCreatinineDto.getUrea())
                                                                  .creatinine(newUreaAndCreatinineDto.getCreatinine())
                                                                  .reportDate(newUreaAndCreatinineDto.getReportDate())
                                                                  .medicalFile(medicalFile)
                                                                  .build();

        ureaAndCreatinineRepository.save(newUreaAndCreatinine);
    }

    public BarChart getUreaAndCreatinineChartInfo(@NotNull List<UreaAndCreatinineDto> data) {
        List<UreaAndCreatinineDto> sortedData = data.parallelStream()
                                                    .sorted(Comparator.comparing(UreaAndCreatinineDto::getReportDate))
                                                    .toList();

        String[] dateLabels = sortedData.stream().map(UreaAndCreatinineDto::getReportDate).map(Object::toString)
                                        .toArray(String[]::new);

        final BarData barData = new BarData();
        barData.setLabels(dateLabels);
        barData.addDataset(new BarDataset().setLabel("Urea")
                                           .setData(getUreaValue(data))
                                           .setBackgroundColor("rgba(191, 6, 3, 1)"));
        barData.addDataset(new BarDataset().setLabel("Creatinine")
                                           .setData(getCreatinineValue(data))
                                           .setBackgroundColor("rgba(244, 213, 141, 1)"));
        barData.addDataset(new BarDataset().setLabel("Urea & Creatinine Ratio")
                                           .setData(getUreaAndCreatinineRatioValue(data))
                                           .setBackgroundColor("rgba(112, 141, 129, 1)"));

        final Plugins legendPlugin = new Plugins().setLegend(new LegendOptions().setPosition("bottom"));

        return new BarChart().setData(barData).setOptions(new BarOptions()
                                                                  .setPlugins(legendPlugin)
                                                                  .setResponsive(true)
                                                                  .setMaintainAspectRatio(false));
    }

    private Double @NotNull [] getUreaAndCreatinineRatioValue(@NotNull List<UreaAndCreatinineDto> data) {
        return data.parallelStream()
                   .map(UreaAndCreatinineDto::getUreaCreatinineRatio)
                   .toArray(Double[]::new);
    }

    private Double @NotNull [] getCreatinineValue(@NotNull List<UreaAndCreatinineDto> data) {
        return data.parallelStream().map(UreaAndCreatinineDto::getCreatinine)
                   .toArray(Double[]::new);
    }

    private Double @NotNull [] getUreaValue(@NotNull List<UreaAndCreatinineDto> data) {
        return data.parallelStream()
                   .map(UreaAndCreatinineDto::getUrea)
                   .toArray(Double[]::new);
    }
}
