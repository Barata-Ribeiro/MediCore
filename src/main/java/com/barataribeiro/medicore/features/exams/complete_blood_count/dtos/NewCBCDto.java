package com.barataribeiro.medicore.features.exams.complete_blood_count.dtos;

import com.barataribeiro.medicore.features.exams.complete_blood_count.CompleteBloodCount;
import com.fasterxml.jackson.annotation.JsonFormat;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.PositiveOrZero;
import lombok.Data;
import org.springframework.format.annotation.DateTimeFormat;

import java.util.Date;

/**
 * DTO for {@link CompleteBloodCount}
 */
@Data
@JsonIgnoreProperties(ignoreUnknown = true)
public class NewCBCDto {

    @NotNull(message = "Hematocrit value is required")
    @PositiveOrZero(message = "Hematocrit must be a positive value or zero")
    private Double hematocrit;

    @NotNull(message = "Hemoglobin value is required")
    @PositiveOrZero(message = "Hemoglobin must be a positive value or zero")
    private Double hemoglobin;

    @NotNull(message = "Red blood cells value is required")
    @PositiveOrZero(message = "Red blood cells must be a positive value or zero")
    private Double redBloodCells;

    @NotNull(message = "Mean corpuscular volume value is required")
    @PositiveOrZero(message = "Mean corpuscular volume must be a positive value or zero")
    private Double meanCorpuscularVolume;

    @NotNull(message = "Mean corpuscular hemoglobin value is required")
    @PositiveOrZero(message = "Mean corpuscular hemoglobin must be a positive value or zero")
    private Double meanCorpuscularHemoglobin;

    @NotNull(message = "Mean corpuscular hemoglobin concentration value is required")
    @PositiveOrZero(message = "Mean corpuscular hemoglobin concentration must be a positive value or zero")
    private Double meanCorpuscularHemoglobinConcentration;

    @NotNull(message = "Red cell distribution width value is required")
    @PositiveOrZero(message = "Red cell distribution width must be a positive value or zero")
    private Double redCellDistributionWidth;

    @NotNull(message = "Leukocytes value is required")
    @PositiveOrZero(message = "Leukocytes must be a positive value or zero")
    private Double leukocytes;

    @NotNull(message = "Rod neutrophils value is required")
    @PositiveOrZero(message = "Rod neutrophils must be a positive value or zero")
    private Double rodNeutrophils;

    @NotNull(message = "Segmented neutrophils value is required")
    @PositiveOrZero(message = "Segmented neutrophils must be a positive value or zero")
    private Double segmentedNeutrophils;

    @NotNull(message = "Lymphocytes value is required")
    @PositiveOrZero(message = "Lymphocytes must be a positive value or zero")
    private Double lymphocytes;

    @NotNull(message = "Atypical lymphocytes value is required")
    @PositiveOrZero(message = "Atypical lymphocytes must be a positive value or zero")
    private Double atypicalLymphocytes;

    @NotNull(message = "Monocytes value is required")
    @PositiveOrZero(message = "Monocytes must be a positive value or zero")
    private Double monocytes;

    @NotNull(message = "Eosinophils value is required")
    @PositiveOrZero(message = "Eosinophils must be a positive value or zero")
    private Double eosinophils;

    @NotNull(message = "Basophils value is required")
    @PositiveOrZero(message = "Basophils must be a positive value or zero")
    private Double basophils;

    @NotNull(message = "Metamyelocytes value is required")
    @PositiveOrZero(message = "Metamyelocytes must be a positive value or zero")
    private Double metamyelocytes;

    @NotNull(message = "Myelocytes value is required")
    @PositiveOrZero(message = "Myelocytes must be a positive value or zero")
    private Double myelocytes;

    @NotNull(message = "Promyelocytes value is required")
    @PositiveOrZero(message = "Promyelocytes must be a positive value or zero")
    private Double promyelocytes;

    @NotNull(message = "Atypical cells value is required")
    @PositiveOrZero(message = "Atypical cells must be a positive value or zero")
    private Double atypicalCells;

    @NotNull(message = "Platelets value is required")
    @PositiveOrZero(message = "Platelets must be a positive value or zero")
    private Double platelets;

    @DateTimeFormat(pattern = "yyyy-MM-dd")
    @JsonFormat(pattern = "yyyy-MM-dd")
    private Date reportDate;
}
