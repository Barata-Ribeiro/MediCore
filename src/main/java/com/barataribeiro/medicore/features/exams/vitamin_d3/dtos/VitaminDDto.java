package com.barataribeiro.medicore.features.exams.vitamin_d3.dtos;

import com.barataribeiro.medicore.features.exams.vitamin_d3.VitaminD;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.io.Serializable;
import java.util.Date;

/**
 * DTO for {@link VitaminD}
 */
@Data
@NoArgsConstructor
@AllArgsConstructor
@JsonIgnoreProperties(ignoreUnknown = true)
public class VitaminDDto implements Serializable {
    private Long id;
    private Double twentyfiveHydroxyvitaminD3;
    private Date reportDate;
}