package com.barataribeiro.medicore.features.exams.vitamin_d3.dtos;

import com.barataribeiro.medicore.features.exams.vitamin_d3.VitaminD;
import com.fasterxml.jackson.annotation.JsonFormat;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.PositiveOrZero;
import lombok.Data;
import org.springframework.format.annotation.DateTimeFormat;

import java.util.Date;

/**
 * DTO for {@link VitaminD}
 */
@Data
@JsonIgnoreProperties(ignoreUnknown = true)
public class NewVitaminDProfileDto {
    @NotNull(message = "25-hydroxyvitamin D3 value is required")
    @NotBlank(message = "25-hydroxyvitamin D3 value is required")
    @PositiveOrZero(message = "25-hydroxyvitamin D3 must be a positive value or zero")
    private Double twentyfiveHydroxyvitaminD3;

    @DateTimeFormat(pattern = "yyyy-MM-dd")
    @JsonFormat(pattern = "yyyy-MM-dd")
    private Date reportDate;
}
