package com.barataribeiro.medicore.features.medical_file.dtos;

import com.barataribeiro.medicore.features.exams.complete_blood_count.dtos.CompleteBloodCountDto;
import com.barataribeiro.medicore.features.exams.glucose.dtos.GlucoseDto;
import com.barataribeiro.medicore.features.exams.lipid_profile.dtos.LipidProfileDto;
import com.barataribeiro.medicore.features.exams.ultrasensitive_tsh.dtos.UltrasensitiveTSHDto;
import com.barataribeiro.medicore.features.exams.urea_and_creatinine.dtos.UreaAndCreatinineDto;
import com.barataribeiro.medicore.features.exams.uric_acid.dtos.UricAcidDto;
import com.barataribeiro.medicore.features.exams.vitamin_b12.dtos.VitaminBTwelveDto;
import com.barataribeiro.medicore.features.exams.vitamin_d3.dtos.VitaminDDto;
import com.barataribeiro.medicore.features.medical_file.MedicalFile;
import com.barataribeiro.medicore.features.user.dtos.AppUserDto;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.io.Serializable;
import java.time.Instant;
import java.util.LinkedHashSet;
import java.util.Set;
import java.util.UUID;

/**
 * DTO for {@link MedicalFile}
 */
@Data
@NoArgsConstructor
@AllArgsConstructor
@JsonIgnoreProperties(ignoreUnknown = true)
public class CompleteMedicalFileDto implements Serializable {
    private UUID id;
    private AppUserDto user;
    private String bloodType;
    private String allergies;
    private String diseases;
    private String medications;
    private Double weight;
    private Double height;
    private Double bmi;
    private String emergencyContactName;
    private String emergencyContactPhone;
    private Instant createdAt;
    private Instant updatedAt;

    private Set<LipidProfileDto> lipidProfiles = new LinkedHashSet<>();
    private Set<CompleteBloodCountDto> completeBloodCounts = new LinkedHashSet<>();
    private Set<GlucoseDto> glucoses = new LinkedHashSet<>();
    private Set<VitaminDDto> vitaminDs = new LinkedHashSet<>();
    private Set<VitaminBTwelveDto> vitaminBTwelves = new LinkedHashSet<>();
    private Set<UreaAndCreatinineDto> ureaAndCreatinines = new LinkedHashSet<>();
    private Set<UricAcidDto> uricAcids = new LinkedHashSet<>();
    private Set<UltrasensitiveTSHDto> ultrasensitiveTSHs = new LinkedHashSet<>();
}