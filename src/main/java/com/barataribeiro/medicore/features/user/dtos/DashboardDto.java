package com.barataribeiro.medicore.features.user.dtos;

import com.barataribeiro.medicore.features.medical_file.dtos.MedicalFileDto;
import com.barataribeiro.medicore.features.user.Profile;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.io.Serializable;

/**
 * DTO for {@link Profile}
 */
@Data
@Builder
@NoArgsConstructor
@AllArgsConstructor
@JsonIgnoreProperties(ignoreUnknown = true)
public class DashboardDto implements Serializable {
    private ProfileDto profile;
    private MedicalFileDto medicalFile;
    private Long totalLipidProfiles;
    private Long totalCompleteBloodCounts;
    private Long totalGlucoses;
    private Long totalVitaminDs;
    private Long totalVitaminBTwelves;
    private Long totalUreaAndCreatinines;
    private Long totalUricAcids;
    private Long totalUltrasensitiveTSHs;
    private Long totalMedicalExams;
}