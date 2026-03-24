package com.barataribeiro.medicore.features.exams.uric_acid;

import com.barataribeiro.medicore.features.exams.uric_acid.dtos.UricAcidDto;
import lombok.RequiredArgsConstructor;
import org.modelmapper.ModelMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class UricAcidMapper {
    private final ModelMapper modelMapper;

    public UricAcidDto toUricAcidDto(UricAcid uricAcid) {
        return modelMapper.map(uricAcid, UricAcidDto.class);
    }
}
