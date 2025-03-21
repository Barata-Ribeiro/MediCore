package com.barataribeiro.medicore.features.exams.uric_acid.dtos;

import com.barataribeiro.medicore.features.exams.uric_acid.UricAcid;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.io.Serializable;
import java.util.Date;

/**
 * DTO for {@link UricAcid}
 */
@Data
@NoArgsConstructor
@AllArgsConstructor
@JsonIgnoreProperties(ignoreUnknown = true)
public class UricAcidDto implements Serializable {
    private Long id;
    private Double uricAcidLevel;
    private Date reportDate;
}