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