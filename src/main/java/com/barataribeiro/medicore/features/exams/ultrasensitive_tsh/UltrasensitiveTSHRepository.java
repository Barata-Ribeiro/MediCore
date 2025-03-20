package com.barataribeiro.medicore.features.exams.ultrasensitive_tsh;

import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.EntityGraph;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.transaction.annotation.Transactional;

import java.util.Optional;

public interface UltrasensitiveTSHRepository extends JpaRepository<UltrasensitiveTSH, Long> {
    @EntityGraph(attributePaths = {"medicalFile.user"})
    Optional<UltrasensitiveTSH> findByMedicalFile_User_Username(String username);

    @EntityGraph(attributePaths = {"medicalFile.user"})
    Page<UltrasensitiveTSH> findAllByMedicalFile_User_Username(String username, Pageable pageable);

    @Transactional
    void deleteByIdAndMedicalFile_User_Username(Long id, String medicalFileUserUsername);
}