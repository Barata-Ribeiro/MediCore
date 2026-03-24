package com.barataribeiro.medicore.features.exams.urea_and_creatinine;

import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.EntityGraph;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.Optional;

public interface UreaAndCreatinineRepository extends JpaRepository<UreaAndCreatinine, Long> {
    @EntityGraph(attributePaths = {"medicalFile.user"})
    Optional<UreaAndCreatinine> findByMedicalFile_User_Username(String username);

    @EntityGraph(attributePaths = {"medicalFile.user"})
    Page<UreaAndCreatinine> findAllByMedicalFile_User_Username(String username, Pageable pageable);

    void deleteByIdAndMedicalFile_User_Username(Long id, String username);
}