package com.barataribeiro.medicore.features.exams.uric_acid;

import com.barataribeiro.medicore.features.exams.uric_acid.dtos.NewUricAcidProfileDto;
import com.barataribeiro.medicore.features.exams.uric_acid.dtos.UricAcidDto;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.jetbrains.annotations.NotNull;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Sort;
import org.springframework.security.core.Authentication;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

@Service
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class UricAcidService {
    private final UricAcidRepository uricAcidRepository;
    private final UricAcidMapper uricAcidMapper;

    @Transactional(readOnly = true)
    public Page<UricAcidDto> getUricAcidPaginated(int page, int perPage, @NotNull String direction, String orderBy,
                                                  @NotNull Authentication authentication) {
        Sort.Direction sortDirection = direction.equalsIgnoreCase("DESC") ? Sort.Direction.DESC : Sort.Direction.ASC;
        orderBy = orderBy.equalsIgnoreCase("reportDate") ? "reportDate" : orderBy;
        PageRequest pageable = PageRequest.of(page, perPage, Sort.by(sortDirection, orderBy));

        Page<UricAcid> uricAcidPage = uricAcidRepository.findAllByMedicalFile_User_Username(authentication.getName(),
                                                                                            pageable);

        return uricAcidPage.map(uricAcidMapper::toUricAcidDto);
    }

    @Transactional
    public void addUricAcid(@Valid NewUricAcidProfileDto newUricAcidProfileDto, String username) {
        // TODO: Implement this method
    }
}
