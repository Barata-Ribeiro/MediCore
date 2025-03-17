package com.barataribeiro.medicore.features.exams.urea_and_creatinine.dtos;

import com.barataribeiro.medicore.features.exams.urea_and_creatinine.UreaAndCreatinine;
import com.fasterxml.jackson.annotation.JsonFormat;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.PositiveOrZero;
import lombok.Data;
import org.springframework.format.annotation.DateTimeFormat;

import java.util.Date;

/**
 * DTO for {@link UreaAndCreatinine}
 */
@Data
@JsonIgnoreProperties(ignoreUnknown = true)
public class NewUreaAndCreatinineDto {

    @NotNull(message = "Urea level value is required")
    @NotBlank(message = "Urea level value is required")
    @PositiveOrZero(message = "Urea level must be a positive value or zero")
    private Double urea;

    @NotNull(message = "Creatinine level value is required")
    @NotBlank(message = "Creatinine level value is required")
    @PositiveOrZero(message = "Creatinine level must be a positive value or zero")
    private Double creatinine;

    @DateTimeFormat(pattern = "yyyy-MM-dd")
    @JsonFormat(pattern = "yyyy-MM-dd")
    private Date reportDate;
}
