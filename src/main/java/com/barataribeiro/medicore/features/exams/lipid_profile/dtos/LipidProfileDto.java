package com.barataribeiro.medicore.features.exams.lipid_profile.dtos;

import com.barataribeiro.medicore.features.exams.lipid_profile.LipidProfile;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.io.Serializable;
import java.util.Date;

/**
 * DTO for {@link LipidProfile}
 */
@Data
@NoArgsConstructor
@AllArgsConstructor
@JsonIgnoreProperties(ignoreUnknown = true)
public class LipidProfileDto implements Serializable {
    private Long id;
    private Double totalCholesterol;
    private Double hdlCholesterol;
    private Double ldlCholesterol;
    private Double vldlCholesterol;
    private Double triglycerides;
    private Date reportDate;
}