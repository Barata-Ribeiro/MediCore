package com.barataribeiro.medicore.features.user;

import com.barataribeiro.medicore.features.medical_file.MedicalFileRepository;
import com.barataribeiro.medicore.features.medical_file.dtos.MedicalFileDto;
import com.barataribeiro.medicore.features.user.dtos.AppUserDto;
import com.barataribeiro.medicore.features.user.dtos.DashboardDto;
import com.barataribeiro.medicore.features.user.dtos.ProfileDto;
import jakarta.annotation.PostConstruct;
import lombok.RequiredArgsConstructor;
import org.modelmapper.ModelMapper;
import org.modelmapper.PropertyMap;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class UserMapper {
    private final ModelMapper modelMapper;
    private final MedicalFileRepository medicalFileRepository;

    @PostConstruct
    public void setModelMapper() {
        modelMapper.addMappings(new PropertyMap<Profile, DashboardDto>() {
            @Override
            protected void configure() {
                using(ctx -> modelMapper.map(ctx.getSource(), ProfileDto.class))
                        .map(source, destination.getProfile());

                using(ctx -> {
                    Profile source = (Profile) ctx.getSource();
                    if (source.getUser() == null || source.getUser().getMedicalFile() == null)
                        return null;
                    return modelMapper.map(source.getUser().getMedicalFile(), MedicalFileDto.class);
                }).map(source, destination.getMedicalFile());

                using(ctx -> {
                    Profile source = (Profile) ctx.getSource();
                    return medicalFileRepository.countTotalExams(source.getUser().getUsername());
                }).map(source, destination.getTotalExams());
            }
        });
    }

    public AppUserDto toUserDto(AppUser user) {
        return modelMapper.map(user, AppUserDto.class);
    }

    public ProfileDto toUserProfileDto(Profile profile) {
        return modelMapper.map(profile, ProfileDto.class);
    }

    public DashboardDto toDashboardDto(Profile profile) {
        return modelMapper.map(profile, DashboardDto.class);
    }
}
