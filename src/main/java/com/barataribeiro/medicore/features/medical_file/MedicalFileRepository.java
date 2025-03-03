package com.barataribeiro.medicore.features.medical_file;

import org.springframework.data.jpa.repository.EntityGraph;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;

import java.util.Optional;
import java.util.UUID;

public interface MedicalFileRepository extends JpaRepository<MedicalFile, UUID> {
    @EntityGraph(attributePaths = {"user.profile"})
    Optional<MedicalFile> findByUser_Username(String username);

    @Query("SELECT " +
            "COUNT(lp) + COUNT(cbc) + COUNT(g) + COUNT(vd) + COUNT(vb) + COUNT(uac) + COUNT(ua) " +
            "FROM MedicalFile mf " +
            "LEFT JOIN mf.lipidProfiles lp " +
            "LEFT JOIN mf.completeBloodCounts cbc " +
            "LEFT JOIN mf.glucoses g " +
            "LEFT JOIN mf.vitaminDs vd " +
            "LEFT JOIN mf.vitaminB12s vb " +
            "LEFT JOIN mf.ureaAndCreatinines uac " +
            "LEFT JOIN mf.uricAcids ua " +
            "WHERE mf.user.username = :username")
    Long countTotalExams(String username);
}