<?php

declare(strict_types=1);

return [
    'head_title' => 'Painel',
    'greeting' => [
        'morning' => 'Bom dia',
        'afternoon' => 'Boa tarde',
        'evening' => 'Boa noite',
        'message' => 'Aqui está uma visão geral rápida da sua conta e atividade recente.',
    ],
    'profile_card' => [
        'empty' => [
            'title' => 'Sem informações de perfil',
            'message' => 'Por favor, complete seu perfil para acessar todos os recursos.',
            'action' => 'Completar Perfil',
        ],
        'card' => [
            'title' => 'Resumo do Perfil',
            'description' => 'Uma visão geral das informações do seu perfil.',
            'full_name' => 'Nome Completo',
            'sex' => 'Sexo',
            'date_of_birth' => 'Data de Nascimento',
            'phone_number' => 'Número de Telefone',
            'address' => 'Endereço',
            'email' => 'Email',
            'updated_at' => 'Última Atualização:',
            'edit_action' => 'Editar Perfil',
        ],
    ],
    'bmi_card' => [
        'empty' => [
            'title' => 'Sem Informações de Altura ou Peso',
            'message' => 'Parece que seu prontuário médico está faltando informações de altura e peso, que são necessárias para calcular seu IMC',
            'action' => 'Atualizar Prontuário Médico',
        ],
        'card' => [
            'title' => 'Índice de Massa Corporal (IMC)',
            'description' => 'Seu IMC é uma medida de gordura corporal baseada na sua altura e peso.',
            'label' => 'Barra de progresso do IMC',
            'category' => [
                'underweight' => 'Abaixo do peso',
                'normal' => 'Peso normal',
                'overweight' => 'Sobrepeso',
                'obesity_class_1' => 'Obesidade Classe I',
                'obesity_class_2' => 'Obesidade Classe II',
                'obesity_class_3' => 'Obesidade Classe III',
            ],
            'height' => 'Altura',
            'weight' => 'Peso',
            'action' => 'Atualizar Prontuário Médico',
        ],
    ],
    'medical_file_card' => [
        'empty' => [
            'title' => 'Sem Informações do Prontuário Médico',
            'message' => 'Por favor, complete seu prontuário médico para acessar todos os recursos.',
            'action' => 'Completar Prontuário Médico',
        ],
        'card' => [
            'title' => 'Resumo do Prontuário Médico',
            'description' => 'Uma visão geral das informações do seu prontuário médico.',
            'blood_type' => 'Tipo Sanguíneo',
            'allergies' => 'Alergias',
            'diseases' => 'Doenças',
            'medications' => 'Medicações',
            'updated_at' => 'Última Atualização:',
            'edit_action' => 'Editar Prontuário Médico',
        ],
    ],
    'exams_summary_card' => [
        'title' => 'Exames Recentes',
        'description' => 'Uma visão geral rápida dos seus exames médicos recentes.',
        'recorded' => 'exames registrados',
    ],
    'exams_made_card' => [
        'title' => 'Exames Realizados',
        'description' => 'Um gráfico mostrando o número de todos os exames realizados por você.',
        'made' => 'exames realizados',
        'chart_label' => 'Exames',
        'chart_empty' => 'Nenhum exame ainda',
    ],
    'card_not_informed' => 'Sem informações',
];
