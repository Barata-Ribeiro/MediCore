package com.barataribeiro.medicore.features.user;

import org.springframework.data.jpa.repository.EntityGraph;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;

import java.util.Optional;
import java.util.UUID;

public interface ProfileRepository extends JpaRepository<Profile, UUID> {
    @EntityGraph(attributePaths = {"user"})
    Optional<Profile> findByUser_Username(String username);

    @EntityGraph(attributePaths = {"user"})
    boolean existsByUser_Username(String username);

    @EntityGraph(attributePaths = {"user.profile", "user.medicalFile"})
    @Query("SELECT p FROM Profile p WHERE p.user.username = :username")
    Optional<Profile> getProfileWithMedicalFile(String username);


}