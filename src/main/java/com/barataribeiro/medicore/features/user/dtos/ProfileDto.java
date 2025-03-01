package com.barataribeiro.medicore.features.user.dtos;

import com.barataribeiro.medicore.features.user.Profile;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.io.Serializable;
import java.util.Date;
import java.util.UUID;

/**
 * DTO for {@link Profile}
 */
@Data
@NoArgsConstructor
@AllArgsConstructor
@JsonIgnoreProperties(ignoreUnknown = true)
public class ProfileDto implements Serializable {
    private UUID id;
    private AppUserDto user;
    private String firstName;
    private String lastName;
    private String fullName;
    private Date birthDate;
    private int age;
    private String sex;
    private String title;
    private String biography;
}