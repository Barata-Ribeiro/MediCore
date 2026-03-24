package com.barataribeiro.medicore.features.exams.lipid_profile;

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
@Table(name = "tb_lipid_profile")
public class LipidProfile implements Serializable {
    @Serial
    private static final long serialVersionUID = 1L;

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(updatable = false, nullable = false, unique = true)
    private Long id;

    @Column(name = "total_cholesterol", nullable = false)
    private Double totalCholesterol;

    @Column(name = "hdl_cholesterol", nullable = false)
    private Double hdlCholesterol;

    @Column(name = "ldl_cholesterol", nullable = false)
    private Double ldlCholesterol;

    @Column(name = "vldl_cholesterol", nullable = false)
    private Double vldlCholesterol;

    @Column(name = "triglycerides", nullable = false)
    private Double triglycerides;

    @JsonFormat(pattern = "dd/mm/yyyy")
    @Temporal(TemporalType.DATE)
    @Column(name = "report_date", nullable = false)
    private Date reportDate;

    @ToString.Exclude
    @ManyToOne
    @JoinColumn(name = "medical_file_id")
    private MedicalFile medicalFile;
}