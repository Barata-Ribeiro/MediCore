package com.barataribeiro.medicore.features.medical_file.dtos;

import com.barataribeiro.medicore.features.medical_file.BloodType;
import com.barataribeiro.medicore.features.medical_file.MedicalFile;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.Pattern;
import lombok.Data;

import java.io.Serializable;

/**
 * DTO for {@link MedicalFile}
 */
@Data
@JsonIgnoreProperties(ignoreUnknown = true)
public class UpdateMedicalFileDto implements Serializable {
    @NotBlank(message = "Blood Type must be selected.")
    private BloodType bloodType;

    @NotBlank(message = "Allergies must not be blank.")
    private String allergies;

    @NotBlank(message = "Diseases must not be blank.")
    private String diseases;

    @NotBlank(message = "Medications must not be blank.")
    private String medications;

    @NotBlank(message = "Weight must not be blank.")
    private Double weight;

    @NotBlank(message = "Height must not be blank.")
    private Double height;

    @NotBlank(message = "Emergency Contact Name must not be blank.")
    private String emergencyContactName;

    @NotBlank(message = "Emergency Contact Phone must not be blank.")
    @Pattern(regexp = "^(\\\\+\\\\d{1,3}( )?)?((\\\\(\\\\d{1,3}\\\\))|\\\\d{1,3})[- .]?\\\\d{3,4}[- .]?\\\\d{4}$",
             message = "Emergency Contact Phone must be a valid phone number.")
    private String emergencyContactPhone;
}
