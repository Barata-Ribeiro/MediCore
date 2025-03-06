package com.barataribeiro.medicore.features.exams.complete_blood_count;

import com.barataribeiro.medicore.features.exams.complete_blood_count.dtos.CompleteBloodCountDto;
import lombok.RequiredArgsConstructor;
import org.jetbrains.annotations.NotNull;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Sort;
import org.springframework.security.core.Authentication;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
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
public class CompleteBloodCountService {
    private final CompleteBloodCountRepository completeBloodCountRepository;
    private final CompleteBloodCountMapper completeBloodCountMapper;

    @Transactional(readOnly = true)
    public Page<CompleteBloodCountDto> getCompleteBloodCountPaginated(int page, int perPage, @NotNull String direction,
                                                                      String orderBy,
                                                                      @NotNull Authentication authentication) {
        Sort.Direction sortDirection = direction.equalsIgnoreCase("DESC") ? Sort.Direction.DESC : Sort.Direction.ASC;
        orderBy = orderBy.equalsIgnoreCase("reportDate") ? "reportDate" : orderBy;
        PageRequest pageable = PageRequest.of(page, perPage, Sort.by(sortDirection, orderBy));

        Page<CompleteBloodCount> completeBloodCounts = completeBloodCountRepository
                .findAllByMedicalFile_User_Username(authentication.getName(), pageable);

        return completeBloodCounts.map(completeBloodCountMapper::toCompleteBloodCountDto);
    }

    public LineChart getCompleteBloodCountChart(@NotNull List<CompleteBloodCountDto> data) {
        List<CompleteBloodCountDto> sortedData = data.parallelStream()
                                                     .sorted(Comparator.comparing(CompleteBloodCountDto::getReportDate))
                                                     .toList();

        String[] dataLabels = sortedData.parallelStream().map(profile -> profile.getReportDate().toString())
                                        .toArray(String[]::new);

        final LineData lineData = new LineData();
        lineData.setLabels(dataLabels);
        lineData.addDataset(new LineDataset().setLabel("Hematocrit").setData(getHematocrit(sortedData)));
        lineData.addDataset(new LineDataset().setLabel("Hemoglobin").setData(getHemoglobin(sortedData)));
        lineData.addDataset(new LineDataset().setLabel("Red Blood Cells").setData(getRedBloodCells(sortedData)));
        lineData.addDataset(new LineDataset().setLabel("White Blood Cells").setData(getLeukocytes(sortedData)));
        lineData.addDataset(new LineDataset().setLabel("Platelets").setData(getPlatelets(sortedData)));

        final Plugins legendPlugin = new Plugins().setLegend(new LegendOptions().setPosition("bottom"));

        return new LineChart().setData(lineData).setOptions(new LineOptions().setPlugins(legendPlugin)
                                                                             .setResponsive(true)
                                                                             .setMaintainAspectRatio(false));
    }

    private Double @NotNull [] getPlatelets(@NotNull List<CompleteBloodCountDto> sortedData) {
        return sortedData.parallelStream()
                         .map(CompleteBloodCountDto::getPlatelets)
                         .toArray(Double[]::new);
    }

    private Double @NotNull [] getLeukocytes(@NotNull List<CompleteBloodCountDto> sortedData) {
        return sortedData.parallelStream()
                         .map(CompleteBloodCountDto::getLeukocytes)
                         .toArray(Double[]::new);
    }

    private Double @NotNull [] getRedBloodCells(@NotNull List<CompleteBloodCountDto> sortedData) {
        return sortedData.parallelStream()
                         .map(CompleteBloodCountDto::getRedBloodCells)
                         .toArray(Double[]::new);
    }

    private Double @NotNull [] getHemoglobin(@NotNull List<CompleteBloodCountDto> sortedData) {
        return sortedData.parallelStream()
                         .map(CompleteBloodCountDto::getHemoglobin)
                         .toArray(Double[]::new);
    }

    private Double @NotNull [] getHematocrit(@NotNull List<CompleteBloodCountDto> sortedData) {
        return sortedData.parallelStream()
                         .map(CompleteBloodCountDto::getHematocrit)
                         .toArray(Double[]::new);
    }
}
