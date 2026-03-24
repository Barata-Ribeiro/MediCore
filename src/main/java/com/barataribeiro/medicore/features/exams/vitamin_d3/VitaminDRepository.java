package com.barataribeiro.medicore.features.exams.vitamin_d3;

import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.EntityGraph;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.transaction.annotation.Transactional;

import java.util.Optional;

public interface VitaminDRepository extends JpaRepository<VitaminD, Long> {
    @EntityGraph(attributePaths = {"medicalFile.user"})
    Optional<VitaminD> findByMedicalFile_User_Username(String username);

    @EntityGraph(attributePaths = {"medicalFile.user"})
    Page<VitaminD> findAllByMedicalFile_User_Username(String username, Pageable pageable);

    @Transactional
    void deleteByIdAndMedicalFile_User_Username(Long id, String medicalFileUserUsername);
}