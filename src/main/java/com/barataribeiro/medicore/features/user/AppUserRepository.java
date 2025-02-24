package com.barataribeiro.medicore.features.user;

import org.springframework.data.jpa.repository.JpaRepository;

import java.util.Optional;
import java.util.UUID;

public interface AppUserRepository extends JpaRepository<AppUser, UUID> {
    Optional<AppUser> findByUsername(String username);

    boolean existsByUsername(String username);

    Optional<AppUser> findByEmail(String email);

    boolean existsByEmail(String email);
}