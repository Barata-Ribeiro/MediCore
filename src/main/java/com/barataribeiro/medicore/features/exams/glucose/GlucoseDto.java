package com.barataribeiro.medicore.features.exams.glucose;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.io.Serializable;
import java.util.Date;

/**
 * DTO for {@link Glucose}
 */
@Data
@NoArgsConstructor
@AllArgsConstructor
@JsonIgnoreProperties(ignoreUnknown = true)
public class GlucoseDto implements Serializable {
    private Long id;
    private Double glucoseLevel;
    private Double glycatedHemoglobin;
    private Double estimatedAverageGlucose;
    private Date reportDate;
}