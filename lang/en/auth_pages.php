<?php

return [
    'confirm_password_page' => [
        'head_title' => 'Confirm Password',
        'title' => 'Confirm your password',
        'description' => 'This is a secure area of the application. Please confirm your password before continuing.',
        'form' => [
            'password' => 'Password',
            'submit' => 'Confirm Password',
        ],
    ],
    'forgot_password_page' => [
        'head_title' => 'Forgot Password',
        'title' => 'Forgot password',
        'description' => 'Enter your email to receive a password reset link',
        'form' => [
            'email' => 'Email address',
            'submit' => 'Email password reset link',
        ],
        'return_message_text' => 'Or, return to',
        'return_message_link' => 'log in',
    ],
    'login_page' => [
        'head_title' => 'Login',
        'title' => 'Log in to your account',
        'description' => 'Welcome back! Please enter your credentials to access your account.',
        'form' => [
            'email' => 'Email address',
            'password' => 'Password',
            'password_forgotten_link' => 'Forgot password?',
            'remember_me' => 'Remember me',
            'submit' => 'Log in',
            'register_message_text' => 'Don\'t have an account?',
            'register_message_link' => 'Sign up',
            'two_factor_message_divider' => 'Or',
            'two_factor_message_google' => 'Continue with Google',
        ],
    ],
    'register_page' => [
        'head_title' => 'Register',
        'title' => 'Create an account',
        'description' => 'Enter your details below to create your account',
        'form' => [
            'name' => 'Name',
            'email' => 'Email address',
            'password' => 'Password',
            'password_confirmation' => 'Confirm password',
            'submit' => 'Create account',
            'login_message_text' => 'Already have an account?',
            'login_message_link' => 'Log in',
        ],
    ],
    'reset_password_page' => [
        'head_title' => 'Reset Password',
        'title' => 'Reset password',
        'description' => 'Please enter your new password below',
        'form' => [
            'email' => 'Email address',
            'password' => 'Password',
            'password_confirmation' => 'Confirm password',
            'submit' => 'Reset password',
        ],
    ],
    'two_factor_challenge_page' => [
        'head_title' => 'Two-factor authentication',
        'recovery' => [
            'title' => 'Recovery code',
            'description' => 'Please confirm access to your account by entering one of your emergency recovery codes.',
            'toggle_text' => 'login using an authentication code',
            'form' => [
                'recovery_code_placeholder' => 'Enter recovery code',
            ],
        ],
        'authentication' => [
            'title' => 'Authentication code',
            'description' => 'Enter the authentication code provided by your authenticator application.',
            'toggle_text' => 'login using a recovery code',

        ],
        'submit' => 'Continue',
        'toggle_text_before' => 'or you can ',
    ],
    'verify_email_page' => [
        'head_title' => 'Email verification',
        'title' => 'Verify email',
        'description' => 'Please verify your email address by clicking on the link we just emailed to you.',
        'resend_message' => 'A new verification link has been sent to the email address you provided during registration.',
        'form' => [
            'submit' => 'Resend verification email',
            'logout' => 'Log out',
        ],
    ],
];
