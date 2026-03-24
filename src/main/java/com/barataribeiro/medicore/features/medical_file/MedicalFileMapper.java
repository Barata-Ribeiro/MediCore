package com.barataribeiro.medicore.features.medical_file;

import com.barataribeiro.medicore.features.medical_file.dtos.CompleteMedicalFileDto;
import com.barataribeiro.medicore.features.medical_file.dtos.MedicalFileDto;
import lombok.RequiredArgsConstructor;
import org.modelmapper.ModelMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class MedicalFileMapper {
    private final ModelMapper modelMapper;

    public CompleteMedicalFileDto toCompleteMedicalFileDto(MedicalFile medicalFile) {
        return modelMapper.map(medicalFile, CompleteMedicalFileDto.class);
    }

    public MedicalFileDto toMedicalFileDto(MedicalFile medicalFile) {
        return modelMapper.map(medicalFile, MedicalFileDto.class);
    }
}
