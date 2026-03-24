package com.barataribeiro.medicore.features.exams.vitamin_b12;

import com.barataribeiro.medicore.features.exams.vitamin_b12.dtos.NewVitaminBTwelveProfileDto;
import com.barataribeiro.medicore.features.exams.vitamin_b12.dtos.VitaminBTwelveDto;
import com.barataribeiro.medicore.features.medical_file.MedicalFile;
import com.barataribeiro.medicore.features.medical_file.MedicalFileRepository;
import jakarta.validation.Valid;
import org.jetbrains.annotations.NotNull;
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
public class VitaminBTwelveService {
    private final VitaminBTwelveRepository vitaminBTwelveRepository;
    private final VitaminBTwelveMapper vitaminBTwelveMapper;
    private final MedicalFileRepository medicalFileRepository;

    public VitaminBTwelveService(VitaminBTwelveRepository vitaminBTwelveRepository,
                                 VitaminBTwelveMapper vitaminBTwelveMapper,
                                 MedicalFileRepository medicalFileRepository) {
        this.vitaminBTwelveRepository = vitaminBTwelveRepository;
        this.vitaminBTwelveMapper = vitaminBTwelveMapper;
        this.medicalFileRepository = medicalFileRepository;
    }

    @Transactional(readOnly = true)
    public Page<VitaminBTwelveDto> getVitaminBTwelvePaginated(int page, int perPage, @NotNull String direction,
                                                              String orderBy,
                                                              @NotNull Authentication authentication) {
        Sort.Direction sortDirection = direction.equalsIgnoreCase("DESC") ? Sort.Direction.DESC : Sort.Direction.ASC;
        orderBy = orderBy.equalsIgnoreCase("reportDate") ? "reportDate" : orderBy;
        PageRequest pageable = PageRequest.of(page, perPage, Sort.by(sortDirection, orderBy));

        Page<VitaminBTwelve> vitaminBTwelvePage = vitaminBTwelveRepository
                .findAllByMedicalFile_User_Username(authentication.getName(), pageable);

        return vitaminBTwelvePage.map(vitaminBTwelveMapper::toVitaminBTwelveDto);
    }

    @Transactional
    public void addVitaminBTwelve(@Valid @NotNull NewVitaminBTwelveProfileDto newVitaminBTwelveProfile,
                                  String username) {
        MedicalFile medicalFile = medicalFileRepository
                .findByUser_Username(username).orElseThrow(() -> new RuntimeException("Medical file not found"));

        VitaminBTwelve newVitaminBTwelve = VitaminBTwelve.builder()
                                                         .vitaminBTwelveLevel(newVitaminBTwelveProfile
                                                                                      .getVitaminBTwelveLevel())
                                                         .reportDate(newVitaminBTwelveProfile.getReportDate())
                                                         .medicalFile(medicalFile)
                                                         .build();

        vitaminBTwelveRepository.save(newVitaminBTwelve);
    }

    public BarChart getVitaminBTwelveChartInfo(@NotNull List<VitaminBTwelveDto> data) {
        List<VitaminBTwelveDto> sortedData = data.parallelStream()
                                                 .sorted(Comparator.comparing(VitaminBTwelveDto::getReportDate))
                                                 .toList();

        String[] dateLabels = sortedData.stream().map(VitaminBTwelveDto::getReportDate).map(Object::toString)
                                        .toArray(String[]::new);

        final BarData barData = new BarData();
        barData.setLabels(dateLabels);
        barData.addDataset(new BarDataset().setLabel("Vitamin B12").setData(getVitaminBTwelveLevels(data))
                                           .setBackgroundColor("rgba(1, 1, 122, 1)"));

        final Plugins legendPlugin = new Plugins().setLegend(new LegendOptions().setPosition("bottom"));


        return new BarChart().setData(barData)
                             .setOptions(new BarOptions().setPlugins(legendPlugin)
                                                         .setResponsive(true)
                                                         .setMaintainAspectRatio(false));
    }

    private Double @NotNull [] getVitaminBTwelveLevels(@NotNull List<VitaminBTwelveDto> data) {
        return data.parallelStream().map(VitaminBTwelveDto::getVitaminBTwelveLevel)
                   .toArray(Double[]::new);
    }
}
