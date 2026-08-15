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
            'columns' => [
                'id' => 'ID',
                'name' => 'Nome',
                'muscle_groups' => 'Grupos musculares',
                'video' => 'Vídeo',
                'created_at' => 'Criado em',
            ],
            'open_video' => 'Abrir vídeo',
            'menu' => [
                'open_label' => 'Abrir menu',
                'actions' => 'Ações',
                'edit' => 'Editar',
                'delete' => 'Excluir',
            ],
            'delete_dialog' => [
                'title' => 'Excluir exercício',
                'description' => 'Tem certeza de que deseja excluir este exercício? Esta ação não pode ser desfeita.',
            ],
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
