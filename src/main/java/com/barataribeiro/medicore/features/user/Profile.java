package com.barataribeiro.medicore.features.user;

import com.fasterxml.jackson.annotation.JsonFormat;
import jakarta.persistence.*;
import lombok.*;

import java.io.Serial;
import java.io.Serializable;
import java.util.Date;
import java.util.UUID;

@NoArgsConstructor
@AllArgsConstructor
@Getter
@Setter
@ToString
@Builder
@Entity
@Table(name = "tb_profiles")
public class Profile implements Serializable {
    @Serial
    private static final long serialVersionUID = 1L;

    @Id
    @GeneratedValue(strategy = GenerationType.UUID)
    @Column(updatable = false, nullable = false, unique = true)
    private UUID id;

    @OneToOne(mappedBy = "profile",
              cascade = {CascadeType.PERSIST, CascadeType.MERGE, CascadeType.REFRESH}, orphanRemoval = true)
    private AppUser user;

    @Column(name = "first_name")
    private String firstName;

    @Column(name = "last_name")
    private String lastName;

    @Transient
    private String fullName;

    @JsonFormat(pattern = "dd/mm/yyyy")
    @Temporal(TemporalType.DATE)
    @Column(name = "birth_date")
    private Date birthDate;

    private String sex;
    private String title;

    @Column(length = 600)
    private String biography;

    public String getFullName() {
        return firstName + " " + lastName;
    }
}