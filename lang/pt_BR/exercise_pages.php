<?php

declare(strict_types=1);

return [
    'index' => [
        'head_title' => 'Exercicios',
        'title' => 'Catalogo de Exercicios',
        'description' => 'Gerencie seu catalogo pessoal de exercicios usado nos treinos.',
        'breadcrumbs' => [
            'current' => 'Exercicios',
        ],
        'table' => [
            'title' => 'Exercicios cadastrados',
            'description' => ':count exercicio(s) no seu catalogo.',
            'columns' => [
                'name' => 'Nome',
                'muscle_groups' => 'Grupos musculares',
                'video' => 'Video',
                'actions' => 'Acoes',
            ],
            'open_video' => 'Abrir video',
            'edit' => 'Editar',
            'delete' => 'Excluir',
        ],
    ],
    'form' => [
        'title' => 'Editor de exercicio',
        'description' => 'Crie ou atualize exercicios ligados aos seus proprios grupos musculares.',
        'name' => 'Nome do exercicio',
        'name_placeholder' => 'ex.: Supino reto',
        'description_field' => 'Descricao',
        'description_placeholder' => 'Notas opcionais de execucao e preparacao.',
        'video_url' => 'URL de video',
        'video_url_placeholder' => 'https://',
        'muscle_groups' => 'Grupos musculares',
        'muscle_groups_hint' => 'Selecione um ou mais grupos musculares trabalhados pelo exercicio.',
        'create_action' => 'Adicionar exercicio',
        'update_action' => 'Atualizar exercicio',
        'cancel_action' => 'Cancelar edicao',
    ],
];
