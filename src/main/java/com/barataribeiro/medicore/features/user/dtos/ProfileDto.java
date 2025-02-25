package com.barataribeiro.medicore.features.user.dtos;

import com.barataribeiro.medicore.features.user.AppUser;
import com.barataribeiro.medicore.features.user.Roles;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.io.Serializable;
import java.time.Instant;
import java.util.Date;
import java.util.UUID;

/**
 * DTO for {@link AppUser}
 */
@Data
@NoArgsConstructor
@AllArgsConstructor
@JsonIgnoreProperties(ignoreUnknown = true)
public class ProfileDto implements Serializable {
    private UUID id;
    private String username;
    private String email;
    private String displayName;
    private String fullName;
    private Date birthDate;
    private String avatarUrl;
    private String sex;
    private String title;
    private String biography;
    private Roles role;
    private Instant createdAt;
    private Instant updatedAt;
}