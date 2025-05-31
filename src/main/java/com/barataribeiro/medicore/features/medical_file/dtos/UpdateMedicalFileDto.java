package com.barataribeiro.medicore.features.medical_file.dtos;

import com.barataribeiro.medicore.features.medical_file.BloodType;
import com.barataribeiro.medicore.features.medical_file.MedicalFile;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import jakarta.validation.constraints.Pattern;
import lombok.Data;

import java.io.Serializable;

/**
 * DTO for {@link MedicalFile}
 */
@Data
@JsonIgnoreProperties(ignoreUnknown = true)
public class UpdateMedicalFileDto implements Serializable {
    private BloodType bloodType;
    private String allergies;
    private String diseases;
    private String medications;
    private Double weight;
    private Double height;
    private String emergencyContactName;

    @Pattern(regexp = "^(\\\\+\\\\d{1,3}( )?)?((\\\\(\\\\d{1,3}\\\\))|\\\\d{1,3})[- .]?\\\\d{3,4}[- .]?\\\\d{4}$",
             message = "Emergency Contact Phone must be a valid phone number.")
    private String emergencyContactPhone;
}
