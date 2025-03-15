package com.barataribeiro.medicore.features.user;

import com.barataribeiro.medicore.features.user.dtos.DashboardDao;
import org.springframework.data.jpa.repository.EntityGraph;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;

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


    @Query("""
           SELECT new com.barataribeiro.medicore.features.user.dtos.DashboardDao(
               p,
               m,
               (SELECT COUNT(e) FROM LipidProfile e WHERE e.medicalFile.id = m.id),
               (SELECT COUNT(e) FROM CompleteBloodCount e WHERE e.medicalFile.id = m.id),
               (SELECT COUNT(e) FROM Glucose e WHERE e.medicalFile.id = m.id),
               (SELECT COUNT(e) FROM VitaminD e WHERE e.medicalFile.id = m.id),
               (SELECT COUNT(e) FROM VitaminBTwelve e WHERE e.medicalFile.id = m.id),
               (SELECT COUNT(e) FROM UreaAndCreatinine e WHERE e.medicalFile.id = m.id),
               (SELECT COUNT(e) FROM UricAcid e WHERE e.medicalFile.id = m.id)
           )
           FROM Profile p
           LEFT JOIN FETCH p.user u
           LEFT JOIN u.medicalFile m
           WHERE u.username = :username
           """)
    Optional<DashboardDao> getDashboardDataRaw(@Param("username") String username);

}