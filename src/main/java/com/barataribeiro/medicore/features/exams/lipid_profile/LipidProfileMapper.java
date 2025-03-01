package com.barataribeiro.medicore.features.exams.lipid_profile;

import lombok.RequiredArgsConstructor;
import org.modelmapper.ModelMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class LipidProfileMapper {
    private final ModelMapper modelMapper;

    public LipidProfileDto toLipidProfileDto(LipidProfile lipidProfile) {
        return modelMapper.map(lipidProfile, LipidProfileDto.class);
    }
}
