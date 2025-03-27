package com.barataribeiro.medicore.features.medical_file;

import org.springframework.data.jpa.repository.EntityGraph;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;

import java.util.Optional;
import java.util.UUID;

public interface MedicalFileRepository extends JpaRepository<MedicalFile, UUID> {
    @EntityGraph(attributePaths = {"user.profile"})
    Optional<MedicalFile> findByUser_Username(String username);

    @Query("""
           SELECT DISTINCT mf FROM MedicalFile mf
           LEFT JOIN FETCH mf.user u
           LEFT JOIN FETCH mf.lipidProfiles lp
           LEFT JOIN FETCH mf.completeBloodCounts cbc
           LEFT JOIN FETCH mf.glucoses g
           LEFT JOIN FETCH mf.vitaminDs vd
           LEFT JOIN FETCH mf.vitaminBTwelves vb
           LEFT JOIN FETCH mf.ureaAndCreatinines uac
           LEFT JOIN FETCH mf.uricAcids ua
           LEFT JOIN FETCH mf.ultrasensitiveTSHs utsh
           WHERE mf.user.username = :username
           AND (lp IS NULL OR lp.reportDate = (SELECT MAX(lp2.reportDate) FROM LipidProfile lp2 WHERE lp2.medicalFile = mf))
           AND (cbc IS NULL OR cbc.reportDate = (SELECT MAX(cbc2.reportDate) FROM CompleteBloodCount cbc2 WHERE cbc2.medicalFile = mf))
           AND (g IS NULL OR g.reportDate = (SELECT MAX(g2.reportDate) FROM Glucose g2 WHERE g2.medicalFile = mf))
           AND (vd IS NULL OR vd.reportDate = (SELECT MAX(vd2.reportDate) FROM VitaminD vd2 WHERE vd2.medicalFile = mf))
           AND (vb IS NULL OR vb.reportDate = (SELECT MAX(vb2.reportDate) FROM VitaminBTwelve vb2 WHERE vb2.medicalFile = mf))
           AND (uac IS NULL OR uac.reportDate = (SELECT MAX(uac2.reportDate) FROM UreaAndCreatinine uac2 WHERE uac2.medicalFile = mf))
           AND (ua IS NULL OR ua.reportDate = (SELECT MAX(ua2.reportDate) FROM UricAcid ua2 WHERE ua2.medicalFile = mf))
           AND (utsh IS NULL OR utsh.reportDate = (SELECT MAX(utsh2.reportDate) FROM UltrasensitiveTSH utsh2 WHERE utsh2.medicalFile = mf))
           """)
    Optional<MedicalFile> findMedicalFileByUser_UsernameWithLatestExams(@Param("username") String username);

    @Query("""
            SELECT SIZE(mf.lipidProfiles) +\s
                       SIZE(mf.ureaAndCreatinines) +\s
                       SIZE(mf.vitaminDs) +\s
                       SIZE(mf.vitaminBTwelves) +\s
                       SIZE(mf.glucoses) +\s
                       SIZE(mf.uricAcids) +\s
                       SIZE(mf.completeBloodCounts) +\s
                       SIZE(mf.ultrasensitiveTSHs)
            FROM MedicalFile mf
            WHERE mf.user.username = :username
           \s""")
    long getTotalExamsCount(@Param("username") String username);
}