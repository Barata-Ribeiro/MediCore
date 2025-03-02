package com.barataribeiro.medicore.features.exams.urea_and_creatinine;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.io.Serializable;
import java.util.Date;

/**
 * DTO for {@link UreaAndCreatinine}
 */
@Data
@NoArgsConstructor
@AllArgsConstructor
@JsonIgnoreProperties(ignoreUnknown = true)
public class UreaAndCreatinineDto implements Serializable {
    private Long id;
    private Double urea;
    private Double creatinine;
    private Double ureaCreatinineRatio;
    private Date reportDate;
}