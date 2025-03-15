package com.barataribeiro.medicore.features.exams.uric_acid;

import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.EntityGraph;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.transaction.annotation.Transactional;

import java.util.Optional;

public interface UricAcidRepository extends JpaRepository<UricAcid, Long> {
    @EntityGraph(attributePaths = {"medicalFile.user"})
    Optional<UricAcid> findByMedicalFile_User_Username(String username);

    @EntityGraph(attributePaths = {"medicalFile.user"})
    Page<UricAcid> findAllByMedicalFile_User_Username(String username, Pageable pageable);

    @Transactional
    void deleteByIdAndMedicalFile_User_Username(Long id, String username);
}