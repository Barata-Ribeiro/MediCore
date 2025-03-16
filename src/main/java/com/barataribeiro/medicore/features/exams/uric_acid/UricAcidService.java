package com.barataribeiro.medicore.features.exams.uric_acid;

import com.barataribeiro.medicore.features.exams.uric_acid.dtos.NewUricAcidProfileDto;
import com.barataribeiro.medicore.features.exams.uric_acid.dtos.UricAcidDto;
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
public class UricAcidService {
    private final UricAcidRepository uricAcidRepository;
    private final UricAcidMapper uricAcidMapper;

    @Transactional(readOnly = true)
    public Page<UricAcidDto> getUricAcidPaginated(int page, int perPage, @NotNull String direction, String orderBy,
                                                  @NotNull Authentication authentication) {
        Sort.Direction sortDirection = direction.equalsIgnoreCase("DESC") ? Sort.Direction.DESC : Sort.Direction.ASC;
        orderBy = orderBy.equalsIgnoreCase("reportDate") ? "reportDate" : orderBy;
        PageRequest pageable = PageRequest.of(page, perPage, Sort.by(sortDirection, orderBy));

        Page<UricAcid> uricAcidPage = uricAcidRepository.findAllByMedicalFile_User_Username(authentication.getName(),
                                                                                            pageable);

        return uricAcidPage.map(uricAcidMapper::toUricAcidDto);
    }

    public BarChart getUricAcidChartInfo(@NotNull List<UricAcidDto> data) {
        List<UricAcidDto> sortedData = data.parallelStream()
                                           .sorted(Comparator.comparing(UricAcidDto::getReportDate)).toList();

        String[] dateLabels = sortedData.stream().map(UricAcidDto::getReportDate).map(Object::toString).toArray(
                String[]::new);

        final BarData barData = new BarData();
        barData.setLabels(dateLabels);
        barData.addDataset(new BarDataset().setLabel("Uric Acid")
                                           .setData(getUricAcidLevel(data))
                                           .setBackgroundColor("rgba(231, 111, 41, 1)"));

        final Plugins legendPlugin = new Plugins().setLegend(new LegendOptions().setPosition("bottom"));

        return new BarChart().setData(barData)
                             .setOptions(new BarOptions()
                                                 .setPlugins(legendPlugin)
                                                 .setResponsive(true)
                                                 .setMaintainAspectRatio(false));
    }

    private Double @NotNull [] getUricAcidLevel(@NotNull List<UricAcidDto> data) {
        return data.parallelStream()
                   .map(UricAcidDto::getUricAcidLevel)
                   .toArray(Double[]::new);
    }

    @Transactional
    public void addUricAcid(@Valid NewUricAcidProfileDto newUricAcidProfileDto, String username) {
        // TODO: Implement this method
    }
}
