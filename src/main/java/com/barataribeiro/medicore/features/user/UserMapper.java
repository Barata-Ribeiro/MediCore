package com.barataribeiro.medicore.features.user;

import com.barataribeiro.medicore.features.user.dtos.AppUserDto;
import com.barataribeiro.medicore.features.user.dtos.ProfileDto;
import lombok.RequiredArgsConstructor;
import org.modelmapper.ModelMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class UserMapper {
    private final ModelMapper modelMapper;

    public AppUserDto toUserDto(AppUser user) {
        return modelMapper.map(user, AppUserDto.class);
    }

    public ProfileDto toUserProfileDto(Profile profile) {
        return modelMapper.map(profile, ProfileDto.class);
    }
}
