package com.barataribeiro.medicore.features.user.dtos;

import com.barataribeiro.medicore.features.medical_file.MedicalFile;
import com.barataribeiro.medicore.features.user.Profile;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import lombok.Data;

@Data
@JsonIgnoreProperties(ignoreUnknown = true)
public class DashboardDao {
    private Profile profile;
    private MedicalFile medicalFile;
    private Long lipidProfileCount;
    private Long completeBloodCountCount;
    private Long glucoseCount;
    private Long vitaminDCount;
    private Long vitaminBTwelveCount;
    private Long ureaAndCreatinineCount;
    private Long uricAcidCount;
    private Long ultrasensitiveTSHCount;

    public DashboardDao(Profile profile, MedicalFile medicalFile,
                        Long totalLipidProfiles, Long totalCompleteBloodCounts,
                        Long totalGlucoses, Long totalVitaminDs, Long totalVitaminBTwelves,
                        Long totalUreaAndCreatinines, Long totalUricAcids, Long totalUltrasensitiveTSHs) {
        this.profile = profile;
        this.medicalFile = medicalFile;
        this.lipidProfileCount = totalLipidProfiles;
        this.completeBloodCountCount = totalCompleteBloodCounts;
        this.glucoseCount = totalGlucoses;
        this.vitaminDCount = totalVitaminDs;
        this.vitaminBTwelveCount = totalVitaminBTwelves;
        this.ureaAndCreatinineCount = totalUreaAndCreatinines;
        this.uricAcidCount = totalUricAcids;
        this.ultrasensitiveTSHCount = totalUltrasensitiveTSHs;
    }
}
