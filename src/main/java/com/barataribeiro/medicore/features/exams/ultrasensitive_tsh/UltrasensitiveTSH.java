package com.barataribeiro.medicore.features.exams.ultrasensitive_tsh;

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
@Table(name = "tb_ultrasensitive_tsh")
public class UltrasensitiveTSH implements Serializable {
    @Serial
    private static final long serialVersionUID = 1L;

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(updatable = false, nullable = false, unique = true)
    private Long id;

    @Column(name = "ultrasensitive_tsh_level", nullable = false)
    private Double ultrasensitiveTSHLevel;

    @JsonFormat(pattern = "dd/mm/yyyy")
    @Temporal(TemporalType.DATE)
    @Column(name = "report_date", nullable = false)
    private Date reportDate;

    @ToString.Exclude
    @ManyToOne
    @JoinColumn(name = "medical_file_id")
    private MedicalFile medicalFile;

}