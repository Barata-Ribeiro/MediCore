package com.barataribeiro.medicore.features.exams.lipid_profile;

import com.barataribeiro.medicore.features.exams.lipid_profile.dtos.LipidProfileDto;
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

import java.util.List;

@Service
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class LipidProfileService {


    private final LipidProfileRepository lipidProfileRepository;
    private final LipidProfileMapper lipidProfileMapper;

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

    public LineChart getLipidProfileChartInfo(@NotNull List<LipidProfileDto> data) {
        String[] dateLabels = data.parallelStream()
                                  .map(profile -> profile.getReportDate().toString())
                                  .toArray(String[]::new);

        final LineData lineData = new LineData();
        lineData.addLabels(dateLabels);
        lineData.addDataset(new LineDataset().setLabel("Total Cholesterol").setData(getTotalCholesterol(data)));
        lineData.addDataset(new LineDataset().setLabel("HDL Cholesterol").setData(getHdlCholesterol(data)));
        lineData.addDataset(new LineDataset().setLabel("LDL Cholesterol").setData(getLdlCholesterol(data)));
        lineData.addDataset(new LineDataset().setLabel("VLDL Cholesterol").setData(getVldlCholesterol(data)));
        lineData.addDataset(new LineDataset().setLabel("Triglycerides").setData(getTriglycerides(data)));

        final Plugins legendPlugin = new Plugins().setLegend(new LegendOptions().setPosition("bottom"));

        return new LineChart().setData(lineData)
                              .setOptions(new LineOptions()
                                                  .setPlugins(legendPlugin)
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
