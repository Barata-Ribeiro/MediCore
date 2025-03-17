package com.barataribeiro.medicore.features.exams.vitamin_b12;

import com.barataribeiro.medicore.features.exams.vitamin_b12.dtos.VitaminBTwelveDto;
import lombok.RequiredArgsConstructor;
import org.modelmapper.ModelMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class VitaminBTwelveMapper {
    private final ModelMapper modelMapper;

    public VitaminBTwelveDto toVitaminBTwelveDto(VitaminBTwelve vitaminBTwelve) {
        return modelMapper.map(vitaminBTwelve, VitaminBTwelveDto.class);
    }
}
