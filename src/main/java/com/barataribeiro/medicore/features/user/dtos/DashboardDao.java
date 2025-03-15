package com.barataribeiro.medicore.features.user.dtos;

import com.barataribeiro.medicore.features.medical_file.MedicalFile;
import com.barataribeiro.medicore.features.user.Profile;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@Builder
@NoArgsConstructor
@AllArgsConstructor
public class DashboardDao {
    private Profile profile;
    private MedicalFile medicalFile;
    private Long totalLipidProfiles;
    private Long totalCompleteBloodCounts;
    private Long totalGlucoses;
    private Long totalVitaminDs;
    private Long totalVitaminBTwelves;
    private Long totalUreaAndCreatinines;
    private Long totalUricAcids;

    public DashboardDao(Profile profile, MedicalFile medicalFile,
                        int totalLipidProfiles, int totalCompleteBloodCounts,
                        int totalGlucoses, int totalVitaminDs, int totalVitaminBTwelves,
                        int totalUreaAndCreatinines, int totalUricAcids) {
        this.profile = profile;
        this.medicalFile = medicalFile;
        this.totalLipidProfiles = (long) totalLipidProfiles;
        this.totalCompleteBloodCounts = (long) totalCompleteBloodCounts;
        this.totalGlucoses = (long) totalGlucoses;
        this.totalVitaminDs = (long) totalVitaminDs;
        this.totalVitaminBTwelves = (long) totalVitaminBTwelves;
        this.totalUreaAndCreatinines = (long) totalUreaAndCreatinines;
        this.totalUricAcids = (long) totalUricAcids;
    }
}
