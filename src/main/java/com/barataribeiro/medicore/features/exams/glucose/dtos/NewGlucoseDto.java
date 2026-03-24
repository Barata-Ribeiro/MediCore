package com.barataribeiro.medicore.features.exams.glucose.dtos;

import com.barataribeiro.medicore.features.exams.glucose.Glucose;
import com.fasterxml.jackson.annotation.JsonFormat;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.PositiveOrZero;
import lombok.Data;
import org.springframework.format.annotation.DateTimeFormat;

import java.util.Date;

/**
 * DTO for {@link Glucose}
 */
@Data
@JsonIgnoreProperties(ignoreUnknown = true)
public class NewGlucoseDto {
    @NotNull(message = "Glucose Level value is required")
    @PositiveOrZero(message = "Glucose level must be a positive value or zero")
    private Double glucoseLevel;

    @NotNull(message = "Glycated Hemoglobin value is required")
    @PositiveOrZero(message = "Glycated Hemoglobin must be a positive value or zero")
    private Double glycatedHemoglobin;

    @NotNull(message = "Estimated Average Glucose value is required")
    @PositiveOrZero(message = "Estimated Average Glucose must be a positive value or zero")
    private Double estimatedAverageGlucose;

    @DateTimeFormat(pattern = "yyyy-MM-dd")
    @JsonFormat(pattern = "yyyy-MM-dd")
    private Date reportDate;
}
