<?php

declare(strict_types=1);

return [
    'index' => [
        'head_title' => 'Grupos Musculares',
        'title' => 'Catalogo de Grupos Musculares',
        'description' => 'Gerencie seus grupos musculares pessoais para classificacao dos exercicios.',
        'breadcrumbs' => [
            'current' => 'Grupos Musculares',
        ],
        'table' => [
            'title' => 'Grupos musculares cadastrados',
            'description' => ':count grupo(s) muscular(es) no seu catalogo.',
            'columns' => [
                'name' => 'Nome',
                'exercises' => 'Exercicios vinculados',
                'actions' => 'Acoes',
            ],
            'edit' => 'Editar',
            'delete' => 'Excluir',
        ],
    ],
    'form' => [
        'title' => 'Editor de grupo muscular',
        'description' => 'Crie ou atualize seus grupos musculares usados nos exercicios.',
        'name' => 'Nome do grupo muscular',
        'name_placeholder' => 'ex.: Peitoral',
        'create_action' => 'Adicionar grupo muscular',
        'update_action' => 'Atualizar grupo muscular',
        'cancel_action' => 'Cancelar edicao',
    ],
];
