<?php

declare(strict_types=1);

return [
    'confirm_password_page' => [
        'head_title' => 'Confirmar senha',
        'title' => 'Confirme sua senha',
        'description' => 'Esta é uma área segura da plataforma. Por favor, confirme sua senha antes de continuar.',
        'form' => [
            'password' => 'Senha',
            'submit' => 'Confirmar senha',
        ],
    ],
    'forgot_password_page' => [
        'head_title' => 'Esqueceu a senha',
        'title' => 'Esqueceu a senha',
        'description' => 'Digite seu e-mail para receber um link de redefinição de senha',
        'form' => [
            'email' => 'E-mail',
            'submit' => 'Enviar link de redefinição de senha',
        ],
        'return_message_text' => 'Ou, volte para',
        'return_message_link' => 'entrar',
    ],
    'login_page' => [
        'head_title' => 'Login',
        'title' => 'Entrar na sua conta',
        'description' => 'Bem-vindo de volta! Por favor, insira suas credenciais para acessar sua conta.',
        'form' => [
            'email' => 'E-mail',
            'password' => 'Senha',
            'password_forgotten_link' => 'Esqueceu a senha?',
            'remember_me' => 'Lembrar-me',
            'submit' => 'Entrar',
            'register_message_text' => 'Não tem uma conta?',
            'register_message_link' => 'Cadastre-se',
            'two_factor_message_divider' => 'Ou',
            'two_factor_message_google' => 'Continuar com o Google',
        ],
    ],
    'register_page' => [
        'head_title' => 'Registrar',
        'title' => 'Criar uma conta',
        'description' => 'Digite seus dados abaixo para criar sua conta',
        'form' => [
            'name' => 'Nome',
            'email' => 'E-mail',
            'password' => 'Senha',
            'password_confirmation' => 'Confirmar senha',
            'submit' => 'Criar conta',
            'login_message_text' => 'Já tem uma conta?',
            'login_message_link' => 'Entrar',
        ],
    ],
    'reset_password_page' => [
        'head_title' => 'Redefinir senha',
        'title' => 'Redefinir senha',
        'description' => 'Por favor, insira sua nova senha abaixo',
        'form' => [
            'email' => 'E-mail',
            'password' => 'Senha',
            'password_confirmation' => 'Confirmar senha',
            'submit' => 'Redefinir senha',
        ],
    ],
    'two_factor_challange_page' => [
        'head_title' => 'Autenticação de dois fatores',
        'recovery' => [
            'title' => 'Código de recuperação',
            'description' => 'Por favor, confirme o acesso à sua conta inserindo um dos seus códigos de recuperação de emergência.',
            'toggle_text' => 'entrar usando um código de autenticação',
            'form' => [
                'recovery_code_placeholder' => 'Digite o código de recuperação',
            ],
        ],
        'authentication' => [
            'title' => 'Código de autenticação',
            'description' => 'Digite o código de autenticação fornecido pelo seu aplicativo autenticador.',
            'toggle_text' => 'entrar usando um código de recuperação',

        ],
        'submit' => 'Continuar',
        'toggle_text_before' => 'ou você pode ',
    ],
    'verify_email_page' => [
        'head_title' => 'Verificação de e-mail',
        'title' => 'Verificar e-mail',
        'description' => 'Por favor, verifique seu endereço de e-mail clicando no link que acabamos de enviar para você.',
        'resend_message' => 'Um novo link de verificação foi enviado para o endereço de e-mail que você forneceu durante o registro.',
        'form' => [
            'submit' => 'Reenviar e-mail de verificação',
            'logout' => 'Sair',
        ],
    ],
];
