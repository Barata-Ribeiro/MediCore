package com.barataribeiro.medicore.features.medicalfile;

import org.springframework.data.jpa.repository.EntityGraph;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.Optional;
import java.util.UUID;

public interface MedicalFileRepository extends JpaRepository<MedicalFile, UUID> {
    @EntityGraph(attributePaths = {"user"})
    Optional<MedicalFile> findByUser_Username(String username);
}