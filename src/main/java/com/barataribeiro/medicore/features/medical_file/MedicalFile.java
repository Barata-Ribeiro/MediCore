package com.barataribeiro.medicore.features.medical_file;

import com.barataribeiro.medicore.features.exams.complete_blood_count.CompleteBloodCount;
import com.barataribeiro.medicore.features.exams.glucose.Glucose;
import com.barataribeiro.medicore.features.exams.lipid_profile.LipidProfile;
import com.barataribeiro.medicore.features.exams.urea_and_creatinine.UreaAndCreatinine;
import com.barataribeiro.medicore.features.exams.uric_acid.UricAcid;
import com.barataribeiro.medicore.features.exams.vitamin_d3.VitaminD;
import com.barataribeiro.medicore.features.exams.vitamind_b12.VitaminBTwelve;
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
@Table(name = "tb_medical_files", indexes = {
        @Index(name = "idx_medicalfile_unq", columnList = "emergency_contact", unique = true)
})
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

    @Enumerated(EnumType.STRING)
    @Column(name = "blood_type", unique = true, length = 12)
    private BloodType bloodType;

    private String allergies;
    private String diseases;
    private String medications;
    private Double weight;
    private Double height;

    @Transient
    private Double bmi;

    @Column(name = "emergency_contact_name", length = 100)
    private String emergencyContactName;

    @Column(name = "emergency_contact_phone", length = 14)
    private String emergencyContactPhone;

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

    @Builder.Default
    @ToString.Exclude
    @OneToMany(mappedBy = "medicalFile", cascade = CascadeType.ALL, orphanRemoval = true, fetch = FetchType.LAZY)
    private Set<VitaminBTwelve> vitaminBTwelves = new LinkedHashSet<>();

    @Builder.Default
    @ToString.Exclude
    @OneToMany(mappedBy = "medicalFile", cascade = CascadeType.ALL, orphanRemoval = true, fetch = FetchType.LAZY)
    private Set<UreaAndCreatinine> ureaAndCreatinines = new LinkedHashSet<>();

    @Builder.Default
    @ToString.Exclude
    @OneToMany(mappedBy = "medicalFile", cascade = CascadeType.ALL, orphanRemoval = true, fetch = FetchType.LAZY)
    private Set<UricAcid> uricAcids = new LinkedHashSet<>();

    public Double getBmi() {
        if (weight == null || (height == null || height == 0)) return null;
        return weight / (height * height);
    }
}