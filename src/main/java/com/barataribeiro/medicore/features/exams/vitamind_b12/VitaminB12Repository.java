package com.barataribeiro.medicore.features.exams.vitamind_b12;

import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.EntityGraph;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.Optional;

public interface VitaminB12Repository extends JpaRepository<VitaminB12, Long> {
    @EntityGraph(attributePaths = {"medicalFile.user"})
    Optional<VitaminB12> findByMedicalFile_User_Username(String username);

    @EntityGraph(attributePaths = {"medicalFile.user"})
    Page<VitaminB12> findAllByMedicalFile_User_Username(String username, Pageable pageable);

    void deleteByIdAndMedicalFile_User_Username(Long id, String username);
}