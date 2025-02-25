package com.barataribeiro.medicore.features.user.dtos;

import com.barataribeiro.medicore.features.user.AppUser;
import com.fasterxml.jackson.annotation.JsonFormat;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import jakarta.validation.constraints.*;
import lombok.Data;
import org.hibernate.validator.constraints.URL;

import java.io.Serializable;
import java.util.Date;

/**
 * DTO for {@link AppUser}
 */
@Data
@JsonIgnoreProperties(ignoreUnknown = true)
public class UpdateAppUserDto implements Serializable {
    @NotNull(message = "Current Password is required")
    @NotBlank(message = "Current Password is required")
    private String currentPassword;

    @Pattern(regexp = "^[a-z]*$", message = "Username must contain only lowercase letters.")
    private String username;

    @Email(regexp = "[A-Za-z0-9._%-+]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,4}",
           message = "You must provide a valid email address.")
    private String email;

    @Size(min = 8, max = 100, message = "Password must be between 8 and 100 characters.")
    @Pattern(message = "Password must contain at least one digit, one lowercase letter, one uppercase letter, one " +
            "special character and no whitespace.",
             regexp = "^(?=.*\\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%¨^&*()\\-_=+])(?=\\S+$).{8,}$")
    private String newPassword;

    @Size(min = 3, max = 50, message = "Display name must be between 3 and 50 characters.")
    @Pattern(regexp = "^[a-zA-Z ]*$", message = "Display name must contain only letters, and spaces.")
    private String displayName;

    @Pattern(regexp = "^[a-zA-Z ]*$", message = "Full name must contain only letters, and spaces.")
    private String fullName;

    @JsonFormat(pattern = "yyyy-MM-dd")
    private Date birthDate;

    @URL(message = "Invalid URL format.", protocol = "https",
         regexp = "(((https?)://)([-%()_.!~*';/?:@&=+$,A-Za-z0-9])+)")
    private String avatarUrl;

    @Pattern(regexp = "^(MALE|FEMALE)$", message = "Sex must be either MALE or FEMALE")
    private String sex;

    @Pattern(regexp = "^[a-zA-Z ]*$", message = "Full name must contain only letters, and spaces.")
    private String title;

    private String bio;
}