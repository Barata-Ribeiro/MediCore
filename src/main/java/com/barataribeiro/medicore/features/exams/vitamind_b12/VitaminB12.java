package com.barataribeiro.medicore.features.exams.vitamind_b12;

import com.barataribeiro.medicore.features.medicalfile.MedicalFile;
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
@Table(name = "tb_vitamin_b_12")
public class VitaminB12 implements Serializable {
    @Serial
    private static final long serialVersionUID = 1L;

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(updatable = false, nullable = false, unique = true)
    private Long id;

    @Column(name = "vitamin_b12", nullable = false)
    private Double vitaminB12;

    @JsonFormat(pattern = "dd/mm/yyyy")
    @Temporal(TemporalType.DATE)
    @Column(name = "report_date", nullable = false)
    private Date reportDate;

    @ToString.Exclude
    @ManyToOne
    @JoinColumn(name = "medical_file_id")
    private MedicalFile medicalFile;

}