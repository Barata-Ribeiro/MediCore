package com.barataribeiro.medicore.features.exams.complete_blood_count;

import com.barataribeiro.medicore.features.exams.complete_blood_count.dtos.CompleteBloodCountDto;
import lombok.RequiredArgsConstructor;
import org.modelmapper.ModelMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class CompleteBloodCountMapper {
    private final ModelMapper modelMapper;

    public CompleteBloodCountDto toCompleteBloodCountDto(CompleteBloodCount completeBloodCount) {
        return modelMapper.map(completeBloodCount, CompleteBloodCountDto.class);
    }
}
