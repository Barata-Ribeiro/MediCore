package com.barataribeiro.medicore.features.exams.urea_and_creatinine;

import com.barataribeiro.medicore.features.exams.urea_and_creatinine.dtos.UreaAndCreatinineDto;
import lombok.RequiredArgsConstructor;
import org.modelmapper.ModelMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class UreaAndCreatinineMapper {
    private final ModelMapper modelMapper;

    public UreaAndCreatinineDto toUreaAndCreatinineDto(UreaAndCreatinine ureaAndCreatinine) {
        return modelMapper.map(ureaAndCreatinine, UreaAndCreatinineDto.class);
    }
}
