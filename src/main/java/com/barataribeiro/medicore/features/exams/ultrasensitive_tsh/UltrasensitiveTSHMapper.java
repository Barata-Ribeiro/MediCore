package com.barataribeiro.medicore.features.exams.ultrasensitive_tsh;

import com.barataribeiro.medicore.features.exams.ultrasensitive_tsh.dtos.UltrasensitiveTSHDto;
import lombok.RequiredArgsConstructor;
import org.modelmapper.ModelMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class UltrasensitiveTSHMapper {
    private final ModelMapper modelMapper;

    public UltrasensitiveTSHDto toUltrasensitiveTSHDto(UltrasensitiveTSH ultrasensitiveTSH) {
        return modelMapper.map(ultrasensitiveTSH, UltrasensitiveTSHDto.class);
    }
}
