package com.barataribeiro.medicore.features.exams.uric_acid.dtos;

import com.barataribeiro.medicore.features.exams.lipid_profile.LipidProfile;
import com.fasterxml.jackson.annotation.JsonFormat;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.PositiveOrZero;
import lombok.Data;
import org.springframework.format.annotation.DateTimeFormat;

import java.util.Date;

/**
 * DTO for {@link LipidProfile}
 */
@Data
@JsonIgnoreProperties(ignoreUnknown = true)
public class NewUricAcidProfileDto {
    @NotNull(message = "Uric acid level value is required")
    @NotBlank(message = "Uric acid level value is required")
    @PositiveOrZero(message = "Uric acid level must be a positive value or zero")
    private Double uricAcidLevel;

    @DateTimeFormat(pattern = "yyyy-MM-dd")
    @JsonFormat(pattern = "yyyy-MM-dd")
    private Date reportDate;
}
