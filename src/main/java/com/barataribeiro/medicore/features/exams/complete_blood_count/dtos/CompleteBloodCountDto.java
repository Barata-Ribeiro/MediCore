package com.barataribeiro.medicore.features.exams.complete_blood_count.dtos;

import com.barataribeiro.medicore.features.exams.complete_blood_count.CompleteBloodCount;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.io.Serializable;
import java.util.Date;

/**
 * DTO for {@link CompleteBloodCount}
 */
@Data
@NoArgsConstructor
@AllArgsConstructor
@JsonIgnoreProperties(ignoreUnknown = true)
public class CompleteBloodCountDto implements Serializable {
    private Long id;
    private Double hematocrit;
    private Double hemoglobin;
    private Double redBloodCells;
    private Double meanCorpuscularVolume;
    private Double meanCorpuscularHemoglobin;
    private Double meanCorpuscularHemoglobinConcentration;
    private Double redCellDistributionWidth;
    private Double leukocytes;
    private Double rodNeutrophils;
    private Double segmentedNeutrophils;
    private Double lymphocytes;
    private Double atypicalLymphocytes;
    private Double monocytes;
    private Double eosinophils;
    private Double basophils;
    private Double metamyelocytes;
    private Double myelocytes;
    private Double promyelocytes;
    private Double atypicalCells;
    private Double platelets;
    private Date reportDate;
}