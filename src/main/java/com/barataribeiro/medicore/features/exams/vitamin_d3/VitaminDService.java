package com.barataribeiro.medicore.features.exams.vitamin_d3;

import com.barataribeiro.medicore.features.exams.vitamin_d3.dtos.NewVitaminDProfileDto;
import com.barataribeiro.medicore.features.exams.vitamin_d3.dtos.VitaminDDto;
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
public class VitaminDService {

    private final VitaminDRepository vitaminDRepository;
    private final VitaminDMapper vitaminDMapper;
    private final MedicalFileRepository medicalFileRepository;

    @Transactional(readOnly = true)
    public Page<VitaminDDto> getVitaminDPaginated(int page, int perPage, @NotNull String direction, String orderBy,
                                                  @NotNull Authentication authentication) {
        Sort.Direction sortDirection = direction.equalsIgnoreCase("DESC") ? Sort.Direction.DESC : Sort.Direction.ASC;
        orderBy = orderBy.equalsIgnoreCase("reportDate") ? "reportDate" : orderBy;
        PageRequest pageable = PageRequest.of(page, perPage, Sort.by(sortDirection, orderBy));

        Page<VitaminD> vitaminDSPage = vitaminDRepository.findAllByMedicalFile_User_Username(authentication.getName(),
                                                                                             pageable);

        return vitaminDSPage.map(vitaminDMapper::toVitaminDDto);
    }

    @Transactional
    public void addVitaminD(@Valid @NotNull NewVitaminDProfileDto newVitaminDProfileDto, String username) {
        MedicalFile medicalFile = medicalFileRepository
                .findByUser_Username(username).orElseThrow(() -> new RuntimeException("Medical file not found"));

        VitaminD newVitaminDProfile = VitaminD.builder()
                                              .twentyfiveHydroxyvitaminD3(newVitaminDProfileDto
                                                                                  .getTwentyfiveHydroxyvitaminD3())
                                              .reportDate(newVitaminDProfileDto.getReportDate())
                                              .medicalFile(medicalFile)
                                              .build();

        vitaminDRepository.save(newVitaminDProfile);
    }

    public BarChart getVitaminDChartInfo(@NotNull List<VitaminDDto> data) {
        List<VitaminDDto> sortedData = data.parallelStream()
                                           .sorted(Comparator.comparing(VitaminDDto::getReportDate)).toList();

        String[] dateLabels = sortedData.stream().map(VitaminDDto::getReportDate).map(Object::toString).toArray(
                String[]::new);

        final BarData barData = new BarData();
        barData.setLabels(dateLabels);
        barData.addDataset(new BarDataset()
                                   .setLabel("25-Hydroxyvitamin D3")
                                   .setData(getVitaminD(data))
                                   .setBackgroundColor("rgba(231, 111, 41, 1)"));

        final Plugins legendPlugin = new Plugins().setLegend(new LegendOptions().setPosition("bottom"));


        return new BarChart().setData(barData)
                             .setOptions(new BarOptions()
                                                 .setPlugins(legendPlugin)
                                                 .setResponsive(true)
                                                 .setMaintainAspectRatio(false));
    }

    private Double @NotNull [] getVitaminD(@NotNull List<VitaminDDto> data) {
        return data.parallelStream()
                   .map(VitaminDDto::getTwentyfiveHydroxyvitaminD3)
                   .toArray(Double[]::new);
    }
}
