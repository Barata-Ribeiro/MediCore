<?php

declare(strict_types=1);

return [
    'index' => [
        'head_title' => 'Exercícios',
        'title' => 'Catálogo de Exercícios',
        'description' => 'Gerencie seu catálogo pessoal de exercícios usado nos treinos.',
        'breadcrumbs' => [
            'current' => 'Exercícios',
        ],
        'table' => [
            'title' => 'Exercícios cadastrados',
            'description' => ':count exercício(s) no seu catálogo.',
            'columns' => [
                'name' => 'Nome',
                'muscle_groups' => 'Grupos musculares',
                'video' => 'Vídeo',
                'actions' => 'Ações',
            ],
            'open_video' => 'Abrir vídeo',
            'edit' => 'Editar',
            'delete' => 'Excluir',
        ],
    ],
    'form' => [
        'title' => 'Editor de exercício',
        'description' => 'Crie ou atualize exercícios ligados aos seus próprios grupos musculares.',
        'name' => 'Nome do exercício',
        'name_placeholder' => 'ex.: Supino reto',
        'description_field' => 'Descrição',
        'description_placeholder' => 'Notas opcionais de execução e preparação.',
        'video_url' => 'URL de vídeo',
        'video_url_placeholder' => 'https://',
        'muscle_groups' => 'Grupos musculares',
        'muscle_groups_hint' => 'Selecione um ou mais grupos musculares trabalhados pelo exercício.',
        'create_action' => 'Adicionar exercício',
        'update_action' => 'Atualizar exercício',
        'cancel_action' => 'Cancelar edição',
    ],
];
