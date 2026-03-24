package com.barataribeiro.medicore.features.user;

import com.fasterxml.jackson.annotation.JsonFormat;
import com.fasterxml.jackson.annotation.JsonIdentityInfo;
import com.fasterxml.jackson.annotation.ObjectIdGenerators;
import jakarta.persistence.*;
import lombok.*;
import org.hibernate.annotations.Fetch;
import org.hibernate.annotations.FetchMode;

import java.io.Serial;
import java.io.Serializable;
import java.time.LocalDate;
import java.time.Period;
import java.util.Date;
import java.util.UUID;

@NoArgsConstructor
@AllArgsConstructor
@Getter
@Setter
@ToString
@Builder
@Entity
@Table(name = "tb_profiles", indexes = {
        @Index(name = "idx_profile_first_name_unq", columnList = "first_name, last_name", unique = true)
})
@JsonIdentityInfo(generator = ObjectIdGenerators.PropertyGenerator.class, property = "id")
public class Profile implements Serializable {
    @Serial
    private static final long serialVersionUID = 1L;

    @Id
    @GeneratedValue(strategy = GenerationType.UUID)
    @Column(updatable = false, nullable = false, unique = true)
    private UUID id;

    @OneToOne(mappedBy = "profile",
              cascade = {CascadeType.PERSIST, CascadeType.MERGE, CascadeType.REFRESH}, orphanRemoval = true)
    @Fetch(FetchMode.JOIN)
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

    @Transient
    private int age;

    private String sex;
    private String title;

    @Column(length = 600)
    private String biography;

    public String getFullName() {
        return (firstName == null && lastName == null) ? null : firstName + " " + lastName;
    }

    public int getAge() {
        if (birthDate == null) return 0;

        LocalDate birthLocalDate = new java.sql.Date(birthDate.getTime()).toLocalDate();
        return Period.between(birthLocalDate, LocalDate.now()).getYears();
    }
}