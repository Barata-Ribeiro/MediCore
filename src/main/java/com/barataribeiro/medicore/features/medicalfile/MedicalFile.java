package com.barataribeiro.medicore.features.medicalfile;

import com.barataribeiro.medicore.features.user.AppUser;
import jakarta.persistence.*;
import lombok.*;
import org.hibernate.annotations.CreationTimestamp;
import org.hibernate.annotations.UpdateTimestamp;

import java.io.Serial;
import java.io.Serializable;
import java.time.Instant;
import java.util.UUID;

@NoArgsConstructor
@AllArgsConstructor
@Getter
@Setter
@ToString
@Builder
@Entity
@Table(name = "tb_medical_files")
public class MedicalFile implements Serializable {
    @Serial
    private static final long serialVersionUID = 1L;

    @Id
    @GeneratedValue(strategy = GenerationType.UUID)
    @Column(updatable = false, nullable = false, unique = true)
    private UUID id;

    @OneToOne(mappedBy = "medicalFile",
              cascade = {CascadeType.PERSIST, CascadeType.MERGE, CascadeType.REFRESH}, optional = false,
              orphanRemoval = true)
    private AppUser user;

    @Column(name = "blood_type", length = 3)
    private String bloodType;

    private String allergies;
    private String diseases;
    private String medications;
    private Double weight;
    private Double height;

    @Transient
    private Double bmi;

    @Column(name = "emergency_contact", length = 14)
    private String emergencyContact;

    @Column(updatable = false)
    @CreationTimestamp
    private Instant createdAt;

    @UpdateTimestamp
    private Instant updatedAt;

    public Double getBmi() {
        return weight / (height * height);
    }
}