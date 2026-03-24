package com.barataribeiro.medicore.features.exams.vitamin_d3;

import com.barataribeiro.medicore.features.exams.vitamin_d3.dtos.VitaminDDto;
import lombok.RequiredArgsConstructor;
import org.modelmapper.ModelMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class VitaminDMapper {
    private final ModelMapper modelMapper;

    public VitaminDDto toVitaminDDto(VitaminD vitaminD) {
        return modelMapper.map(vitaminD, VitaminDDto.class);
    }
}
