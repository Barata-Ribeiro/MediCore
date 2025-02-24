package com.barataribeiro.medicore.features.authentication;

import jakarta.validation.constraints.*;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class RegistrationDto {
    @NotNull(message = "Username is required")
    @NotBlank(message = "Username is required")
    @Pattern(regexp = "^[a-z]*$", message = "Username must contain only lowercase letters.")
    private String username;

    @NotNull(message = "Email is required")
    @NotBlank(message = "Email is required")
    @Email(regexp = "[A-Za-z0-9._%-+]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,4}",
           message = "You must provide a valid email address.")
    private String email;

    @NotNull(message = "Password is required")
    @NotBlank(message = "Password is required")
    @Size(min = 8, max = 100, message = "Password must be between 8 and 100 characters.")
    @Pattern(message = "Password must contain at least one digit, one lowercase letter, one uppercase letter, one " +
            "special character and no whitespace.",
             regexp = "^(?=.*\\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%¨^&*()\\-_=+])(?=\\S+$).{8,}$")
    private String password;

    @NotNull(message = "Password confirmation is required")
    @NotBlank(message = "Password confirmation is required")
    private String passwordConfirmation;

    @Size(min = 3, max = 50, message = "Display name must be between 3 and 50 characters.")
    @Pattern(regexp = "^[a-zA-Z ]*$", message = "Display name must contain only letters, and spaces.")
    private String displayName;
}
