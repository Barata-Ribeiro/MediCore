package com.barataribeiro.medicore.features.exams.urea_and_creatinine;

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
@Table(name = "tb_urea_and_creatinine")
public class UreaAndCreatinine implements Serializable {
    @Serial
    private static final long serialVersionUID = 1L;

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(updatable = false, nullable = false, unique = true)
    private Long id;

    @Column(nullable = false)
    private Double urea;

    @Column(nullable = false)
    private Double creatinine;

    @Transient
    private Double ureaCreatinineRatio;

    @JsonFormat(pattern = "dd/mm/yyyy")
    @Temporal(TemporalType.DATE)
    @Column(name = "report_date", nullable = false)
    private Date reportDate;

    @ToString.Exclude
    @ManyToOne
    @JoinColumn(name = "medical_file_id")
    private MedicalFile medicalFile;

    public Double getUreaCreatinineRatio() {
        return this.urea / this.creatinine;
    }
}