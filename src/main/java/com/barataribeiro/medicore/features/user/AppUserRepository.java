package com.barataribeiro.medicore.features.user;

import org.springframework.data.jpa.repository.EntityGraph;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.Optional;
import java.util.UUID;

public interface AppUserRepository extends JpaRepository<AppUser, UUID> {
    Optional<AppUser> findByUsername(String username);

    boolean existsByUsername(String username);

    Optional<AppUser> findByEmail(String email);

    boolean existsByEmail(String email);

    @EntityGraph(attributePaths = {"profile"})
    Optional<AppUser> findByProfile_FirstNameOrProfile_LastNameAllIgnoreCase(String firstName, String lastName);
}