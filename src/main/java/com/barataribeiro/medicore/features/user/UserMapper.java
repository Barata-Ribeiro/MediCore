package com.barataribeiro.medicore.features.user;

import com.barataribeiro.medicore.features.user.dtos.ProfileDto;
import lombok.RequiredArgsConstructor;
import org.modelmapper.ModelMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class UserMapper {
    private final ModelMapper modelMapper;

    public ProfileDto toUserProfileDTO(AppUser user) {
        return modelMapper.map(user, ProfileDto.class);
    }
}
