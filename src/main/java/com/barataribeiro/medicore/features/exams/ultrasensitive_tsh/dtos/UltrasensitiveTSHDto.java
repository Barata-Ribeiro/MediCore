package com.barataribeiro.medicore.features.exams.ultrasensitive_tsh.dtos;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.io.Serializable;
import java.util.Date;

/**
 * DTO for {@link com.barataribeiro.medicore.features.exams.ultrasensitive_tsh.UltrasensitiveTSH}
 */
@Data
@NoArgsConstructor
@AllArgsConstructor
@JsonIgnoreProperties(ignoreUnknown = true)
public class UltrasensitiveTSHDto implements Serializable {
    private Long id;
    private Double ultrasensitiveTSHLevel;
    private Date reportDate;
}