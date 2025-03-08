package com.barataribeiro.medicore.features.medical_file;

import org.springframework.data.jpa.repository.EntityGraph;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;

import java.util.UUID;

public interface MedicalFileRepository extends JpaRepository<MedicalFile, UUID> {
    @EntityGraph(attributePaths = {"user.profile"})
    MedicalFile findByUser_Username(String username);

    @Query("""
           SELECT SIZE(mf.lipidProfiles) + SIZE(mf.ureaAndCreatinines) + SIZE(mf.vitaminDs) + SIZE(mf.vitaminB12s) + SIZE(mf.glucoses) + SIZE(mf.uricAcids) + SIZE(mf.completeBloodCounts)
           FROM MedicalFile mf
           WHERE mf.user.username = :username
           """)
    long getTotalExamsCount(@Param("username") String username);
}