package com.barataribeiro.medicore.features.exams.vitamind_b12;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.io.Serializable;
import java.util.Date;

/**
 * DTO for {@link VitaminB12}
 */
@Data
@NoArgsConstructor
@AllArgsConstructor
@JsonIgnoreProperties(ignoreUnknown = true)
public class VitaminB12Dto implements Serializable {
    private Long id;
    private Double vitaminB12;
    private Date reportDate;
}