package com.barataribeiro.medicore.features.medical_file.dtos;

import com.barataribeiro.medicore.features.medical_file.BloodType;
import com.barataribeiro.medicore.features.medical_file.MedicalFile;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.io.Serializable;
import java.time.Instant;
import java.util.UUID;

/**
 * DTO for {@link MedicalFile}
 */
@Data
@NoArgsConstructor
@AllArgsConstructor
@JsonIgnoreProperties(ignoreUnknown = true)
public class MedicalFileDto implements Serializable {
    private UUID id;
    private BloodType bloodType;
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
}