package com.barataribeiro.medicore.features.exams.glucose;

import lombok.RequiredArgsConstructor;
import org.modelmapper.ModelMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class GlucoseMapper {
    private final ModelMapper modelMapper;

    public GlucoseDto toGlucoseDto(Glucose glucose) {
        return modelMapper.map(glucose, GlucoseDto.class);
    }
}
