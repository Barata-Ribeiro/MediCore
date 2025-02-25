package com.barataribeiro.medicore.features.user;

import com.fasterxml.jackson.annotation.JsonFormat;
import com.fasterxml.jackson.annotation.JsonIgnore;
import jakarta.persistence.*;
import lombok.*;
import org.hibernate.annotations.CreationTimestamp;
import org.hibernate.annotations.UpdateTimestamp;
import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.security.core.userdetails.UserDetails;

import java.io.Serial;
import java.io.Serializable;
import java.time.Instant;
import java.util.Collection;
import java.util.Collections;
import java.util.Date;
import java.util.UUID;

@NoArgsConstructor
@AllArgsConstructor
@Getter
@Setter
@ToString
@Builder
@Entity
@Table(name = "tb_users", indexes = {
        @Index(name = "idx_user_id_username_email", columnList = "id, username, email"),
        @Index(name = "idx_user_username_email_unq", columnList = "username, email", unique = true)
}, uniqueConstraints = {
        @UniqueConstraint(name = "uc_user_username_email", columnNames = {"username", "email"})
})
public class AppUser implements UserDetails, Serializable {
    @Serial
    private static final long serialVersionUID = 1L;

    @Id
    @GeneratedValue(strategy = GenerationType.UUID)
    @Column(updatable = false, nullable = false, unique = true)
    private UUID id;

    @Column(name = "username", nullable = false, unique = true)
    private String username;

    @ToString.Exclude
    @JsonIgnore
    @Column(name = "password", nullable = false)
    private String password;

    @Column(name = "email", nullable = false, unique = true)
    private String email;

    @Column(name = "display_name")
    private String displayName;

    @Column(name = "full_name")
    private String fullName;

    @JsonFormat(pattern = "dd/mm/yyyy")
    @Temporal(TemporalType.DATE)
    @Column(name = "birth_date")
    private Date birthDate;

    @Column(name = "avatar_url")
    private String avatarUrl;

    private String sex;

    private String title;

    @Column(length = 600)
    private String biography;

    @Builder.Default
    @Enumerated(EnumType.STRING)
    @Column(nullable = false)
    private Roles role = Roles.USER;

    @Column(updatable = false)
    @CreationTimestamp
    private Instant createdAt;

    @UpdateTimestamp
    private Instant updatedAt;

    @Override
    public Collection<? extends GrantedAuthority> getAuthorities() {
        return Collections.singletonList(new SimpleGrantedAuthority("ROLE_" + this.role.name()));
    }

    @Override
    public boolean isAccountNonExpired() {
        return UserDetails.super.isAccountNonExpired();
    }

    @Override
    public boolean isAccountNonLocked() {
        return UserDetails.super.isAccountNonLocked();
    }

    @Override
    public boolean isCredentialsNonExpired() {
        return UserDetails.super.isCredentialsNonExpired();
    }

    @Override
    public boolean isEnabled() {
        return UserDetails.super.isEnabled();
    }
}
