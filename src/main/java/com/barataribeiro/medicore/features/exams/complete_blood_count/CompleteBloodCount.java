package com.barataribeiro.medicore.features.exams.complete_blood_count;

import com.barataribeiro.medicore.features.medical_file.MedicalFile;
import com.fasterxml.jackson.annotation.JsonFormat;
import jakarta.persistence.*;
import lombok.*;

import java.io.Serial;
import java.io.Serializable;
import java.util.Date;

@NoArgsConstructor
@AllArgsConstructor
@Getter
@Setter
@ToString
@Builder
@Entity
@Table(name = "tb_complete_blood_count")
public class CompleteBloodCount implements Serializable {
    @Serial
    private static final long serialVersionUID = 1L;

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(updatable = false, nullable = false, unique = true)
    private Long id;

    @Column(nullable = false)
    private Double hematocrit;

    @Column(nullable = false)
    private Double hemoglobin;

    @Column(name = "red_blood_cells", nullable = false)
    private Double redBloodCells;

    @Column(name = "mean_corpuscular_volume", nullable = false)
    private Double meanCorpuscularVolume;

    @Column(name = "mean_corpuscular_hemoglobin", nullable = false)
    private Double meanCorpuscularHemoglobin;

    @Column(name = "mean_corpuscular_hemoglobin_concentration", nullable = false)
    private Double meanCorpuscularHemoglobinConcentration;

    @Column(name = "red_cell_distribution_width", nullable = false)
    private Double redCellDistributionWidth;

    @Column(nullable = false)
    private Double leukocytes;

    @Column(name = "rod_neutrophils", nullable = false)
    private Double rodNeutrophils;

    @Column(name = "segmented_neutrophils", nullable = false)
    private Double segmentedNeutrophils;

    @Column(nullable = false)
    private Double lymphocytes;

    @Column(name = "atypical_lymphocytes", nullable = false)
    private Double atypicalLymphocytes;

    @Column(nullable = false)
    private Double monocytes;

    @Column(nullable = false)
    private Double eosinophils;

    @Column(nullable = false)
    private Double basophils;

    @Column(nullable = false)
    private Double metamyelocytes;

    @Column(nullable = false)
    private Double myelocytes;

    @Column(nullable = false)
    private Double promyelocytes;

    @Column(name = "atypical_cells", nullable = false)
    private Double atypicalCells;

    @Column(nullable = false)
    private Double platelets;

    @JsonFormat(pattern = "dd/mm/yyyy")
    @Temporal(TemporalType.DATE)
    @Column(name = "report_date", nullable = false)
    private Date reportDate;

    @ToString.Exclude
    @ManyToOne
    @JoinColumn(name = "medical_file_id")
    private MedicalFile medicalFile;

}