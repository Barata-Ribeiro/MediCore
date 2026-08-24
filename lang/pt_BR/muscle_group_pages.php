<?php

declare(strict_types=1);

return [
    'index' => [
        'head_title' => 'Grupos Musculares',
        'title' => 'Catálogo de Grupos Musculares',
        'description' => 'Gerencie seus grupos musculares pessoais para classificação dos exercícios.',
        'breadcrumbs' => [
            'current' => 'Grupos Musculares',
        ],
        'table' => [
            'title' => 'Grupos musculares cadastrados',
            'description' => ':count grupo(s) muscular(es) no seu catálogo.',
            'columns' => [
                'name' => 'Nome',
                'exercises' => 'Exercícios vinculados',
                'created_at' => 'Criado em',
            ],
            'menu' => [
                'open_label' => 'Abrir menu',
                'actions' => 'Ações',
                'edit' => 'Editar',
                'delete' => 'Excluir',
            ],
            'delete_dialog' => [
                'title' => 'Excluir grupo muscular',
                'description' => 'Tem certeza de que deseja excluir este grupo muscular? Esta ação não pode ser desfeita.',
            ],
        ],
    ],
    'form' => [
        'title' => 'Editor de grupo muscular',
        'description' => 'Crie ou atualize seus grupos musculares usados nos exercícios.',
        'name' => 'Nome do grupo muscular',
        'name_placeholder' => 'ex.: Peitoral',
        'create_action' => 'Adicionar grupo muscular',
        'update_action' => 'Atualizar grupo muscular',
        'cancel_action' => 'Cancelar edição',
    ],
];
