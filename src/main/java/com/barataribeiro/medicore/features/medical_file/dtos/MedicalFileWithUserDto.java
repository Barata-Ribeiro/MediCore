package com.barataribeiro.medicore.features.medical_file.dtos;

import com.barataribeiro.medicore.features.medical_file.MedicalFile;
import com.barataribeiro.medicore.features.user.dtos.AppUserDto;
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
public class MedicalFileWithUserDto implements Serializable {
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
}