package com.barataribeiro.medicore.features.medical_file;

import com.barataribeiro.medicore.features.medical_file.dtos.MedicalFileDto;
import com.barataribeiro.medicore.features.medical_file.dtos.MedicalFileWithUserDto;
import lombok.RequiredArgsConstructor;
import org.modelmapper.ModelMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class MedicalFileMapper {
    private final ModelMapper modelMapper;

    public MedicalFileWithUserDto toMedicalFileWithUserDto(MedicalFile medicalFile) {
        return modelMapper.map(medicalFile, MedicalFileWithUserDto.class);
    }

    public MedicalFileDto toMedicalFileDto(MedicalFile medicalFile) {
        return modelMapper.map(medicalFile, MedicalFileDto.class);
    }
}
