package com.barataribeiro.medicore.features.exams.glucose;

import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.EntityGraph;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.transaction.annotation.Transactional;

import java.util.Optional;

public interface GlucoseRepository extends JpaRepository<Glucose, Long> {
    @EntityGraph(attributePaths = {"medicalFile.user"})
    Optional<Glucose> findByMedicalFile_User_Username(String username);

    @EntityGraph(attributePaths = {"medicalFile.user"})
    Page<Glucose> findAllByMedicalFile_User_Username(String username, Pageable pageable);

    @EntityGraph(attributePaths = {"medicalFile.user"})
    @Transactional
    void deleteByIdAndMedicalFile_User_Username(Long id, String medicalFileUserUsername);
}