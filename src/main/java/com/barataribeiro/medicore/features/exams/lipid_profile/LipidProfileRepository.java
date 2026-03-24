package com.barataribeiro.medicore.features.exams.lipid_profile;

import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.EntityGraph;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.transaction.annotation.Transactional;

import java.util.Optional;

public interface LipidProfileRepository extends JpaRepository<LipidProfile, Long> {
    @EntityGraph(attributePaths = {"medicalFile.user"})
    Optional<LipidProfile> findByMedicalFile_User_Username(String username);

    @EntityGraph(attributePaths = {"medicalFile.user"})
    Page<LipidProfile> findAllByMedicalFile_User_Username(String username, Pageable pageable);

    @EntityGraph(attributePaths = {"medicalFile.user"})
    @Transactional
    void deleteByIdAndMedicalFile_User_Username(Long id, String username);
}