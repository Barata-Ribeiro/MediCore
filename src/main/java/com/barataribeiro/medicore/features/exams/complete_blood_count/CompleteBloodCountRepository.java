package com.barataribeiro.medicore.features.exams.complete_blood_count;

import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.EntityGraph;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.Optional;

public interface CompleteBloodCountRepository extends JpaRepository<CompleteBloodCount, Long> {
    @EntityGraph(attributePaths = {"medicalFile.user"})
    Optional<CompleteBloodCount> findByMedicalFile_User_Username(String username);

    @EntityGraph(attributePaths = {"medicalFile.user"})
    Page<CompleteBloodCount> findAllByMedicalFile_User_Username(String username, Pageable pageable);

}