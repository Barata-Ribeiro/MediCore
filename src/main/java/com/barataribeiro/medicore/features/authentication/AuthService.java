package com.barataribeiro.medicore.features.authentication;

import com.barataribeiro.medicore.features.user.AppUser;
import com.barataribeiro.medicore.features.user.AppUserRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.userdetails.User;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.stereotype.Service;

@Service
@RequiredArgsConstructor(onConstructor_ = {@Autowired})
public class AuthService implements UserDetailsService {
    private final AppUserRepository userRespository;

    @Override
    public UserDetails loadUserByUsername(String username) throws UsernameNotFoundException {
        AppUser user = userRespository.findByUsername(username);
        if (user == null) return null;
        return User.withUsername(user.getUsername())
                   .password(user.getPassword())
                   .roles(user.getRole().name())
                   .authorities(user.getAuthorities())
                   .build();
    }
}
