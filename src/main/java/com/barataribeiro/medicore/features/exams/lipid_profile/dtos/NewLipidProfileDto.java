package com.barataribeiro.medicore.features.exams.lipid_profile.dtos;

import com.barataribeiro.medicore.features.user.AppUser;
import com.fasterxml.jackson.annotation.JsonFormat;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.PositiveOrZero;
import lombok.Data;
import org.springframework.format.annotation.DateTimeFormat;

import java.util.Date;

/**
 * DTO for {@link AppUser}
 */
@Data
@JsonIgnoreProperties(ignoreUnknown = true)
public class NewLipidProfileDto {
    @NotNull(message = "Total cholesterol value is required")
    @PositiveOrZero(message = "Total cholesterol must be a positive value or zero")
    private Double totalCholesterol;

    @NotNull(message = "HDL cholesterol value is required")
    @PositiveOrZero(message = "HDL cholesterol must be a positive value or zero")
    private Double hdlCholesterol;

    @NotNull(message = "LDL cholesterol value is required")
    @PositiveOrZero(message = "LDL cholesterol must be a positive value or zero")
    private Double ldlCholesterol;

    @NotNull(message = "VLDL cholesterol value is required")
    @PositiveOrZero(message = "VLDL cholesterol must be a positive value or zero")
    private Double vldlCholesterol;

    @NotNull(message = "Triglycerides value is required")
    @PositiveOrZero(message = "Triglycerides must be a positive value or zero")
    private Double triglycerides;

    @DateTimeFormat(pattern = "yyyy-MM-dd")
    @JsonFormat(pattern = "yyyy-MM-dd")
    private Date reportDate;
}