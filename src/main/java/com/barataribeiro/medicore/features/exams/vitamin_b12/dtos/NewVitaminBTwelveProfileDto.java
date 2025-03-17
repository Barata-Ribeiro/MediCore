package com.barataribeiro.medicore.features.exams.vitamin_b12.dtos;

import com.barataribeiro.medicore.features.exams.vitamin_b12.VitaminBTwelve;
import com.fasterxml.jackson.annotation.JsonFormat;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.PositiveOrZero;
import lombok.Data;
import org.springframework.format.annotation.DateTimeFormat;

import java.util.Date;

/**
 * DTO for {@link VitaminBTwelve}
 */
@Data
@JsonIgnoreProperties(ignoreUnknown = true)
public class NewVitaminBTwelveProfileDto {
    @NotNull(message = "Vitamin B12 value is required")
    @NotBlank(message = "Vitamin B12 value is required")
    @PositiveOrZero(message = "Vitamin B12 must be a positive value or zero")
    private Double vitaminBTwelveLevel;

    @DateTimeFormat(pattern = "yyyy-MM-dd")
    @JsonFormat(pattern = "yyyy-MM-dd")
    private Date reportDate;
}
