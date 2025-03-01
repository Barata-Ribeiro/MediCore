package com.barataribeiro.medicore.features.medicalfile;

import com.barataribeiro.medicore.features.exams.complete_blood_count.CompleteBloodCount;
import com.barataribeiro.medicore.features.exams.glucose.Glucose;
import com.barataribeiro.medicore.features.exams.lipid_profile.LipidProfile;
import com.barataribeiro.medicore.features.exams.vitamin_d3.VitaminD;
import com.barataribeiro.medicore.features.user.AppUser;
import jakarta.persistence.*;
import lombok.*;
import org.hibernate.annotations.CreationTimestamp;
import org.hibernate.annotations.UpdateTimestamp;

import java.io.Serial;
import java.io.Serializable;
import java.time.Instant;
import java.util.LinkedHashSet;
import java.util.Set;
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

    @OneToOne(mappedBy = "medicalFile", cascade = {CascadeType.PERSIST, CascadeType.MERGE, CascadeType.REFRESH},
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

    @Builder.Default
    @ToString.Exclude
    @OneToMany(mappedBy = "medicalFile", cascade = CascadeType.ALL, orphanRemoval = true, fetch = FetchType.LAZY)
    private Set<LipidProfile> lipidProfiles = new LinkedHashSet<>();

    @Builder.Default
    @ToString.Exclude
    @OneToMany(mappedBy = "medicalFile", cascade = CascadeType.ALL, orphanRemoval = true, fetch = FetchType.LAZY)
    private Set<CompleteBloodCount> completeBloodCounts = new LinkedHashSet<>();

    @Builder.Default
    @ToString.Exclude
    @OneToMany(mappedBy = "medicalFile", cascade = CascadeType.ALL, orphanRemoval = true, fetch = FetchType.LAZY)
    private Set<Glucose> glucoses = new LinkedHashSet<>();

    @Builder.Default
    @ToString.Exclude
    @OneToMany(mappedBy = "medicalFile", cascade = CascadeType.ALL, orphanRemoval = true, fetch = FetchType.LAZY)
    private Set<VitaminD> vitaminDs = new LinkedHashSet<>();

    public Double getBmi() {
        return weight / (height * height);
    }
}