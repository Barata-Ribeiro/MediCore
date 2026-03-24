package com.barataribeiro.medicore.features.user.dtos;

import com.barataribeiro.medicore.features.user.AppUser;
import com.fasterxml.jackson.annotation.JsonFormat;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import jakarta.validation.constraints.*;
import lombok.Data;
import org.hibernate.validator.constraints.URL;
import org.springframework.format.annotation.DateTimeFormat;

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

    @Size(min = 3, max = 50, message = "Display name must be between 3 and 50 characters.")
    @Pattern(regexp = "^[a-zA-Z ]*$", message = "Display name must contain only letters, and spaces.")
    private String displayName;

    @Email(regexp = "[A-Za-z0-9._%-+]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,4}",
           message = "You must provide a valid email address.")
    private String email;

    @Size(min = 8, max = 100, message = "Password must be between 8 and 100 characters.")
    @Pattern(message = "Password must contain at least one digit, one lowercase letter, one uppercase letter, one " +
            "special character and no whitespace.",
             regexp = "^(?=.*\\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%¨^&*()\\-_=+])(?=\\S+$).{8,}$")
    private String newPassword;

    private String passwordConfirmation;

    @Pattern(regexp = "^[\\p{L} ]*$", message = "First Name must contain only letters, and spaces.")
    private String firstName;

    @Pattern(regexp = "^[\\p{L} ]*$", message = "Last Name must contain only letters, and spaces.")
    private String lastName;

    @DateTimeFormat(pattern = "yyyy-MM-dd")
    @JsonFormat(pattern = "yyyy-MM-dd")
    private Date birthDate;

    @URL(message = "Invalid URL format.", protocol = "https",
         regexp = "(((https?)://)([-%()_.!~*';/?:@&=+$,A-Za-z0-9])+)")
    private String avatarUrl;

    @Pattern(regexp = "^(MALE|FEMALE)$", message = "Sex must be either MALE or FEMALE")
    private String sex;

    @Pattern(regexp = "^[\\p{L} ]*$", message = "Full name must contain only letters, and spaces.")
    private String title;

    @Size(max = 600, message = "Biography must be at most 600 characters.")
    private String biography;
}