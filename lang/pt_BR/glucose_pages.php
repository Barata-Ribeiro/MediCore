<?php

declare(strict_types=1);

return [
    'shared' => [
        'unit' => 'mg/dL',
        'percentage_unit' => '%',
        'back' => 'Voltar',
        'back_label' => 'Voltar',
    ],
    'index' => [
        'head_title' => 'Exames de Glicose',
        'title' => 'Exames de Glicose',
        'description' => 'Visualize e analise seus resultados de glicose.',
        'breadcrumbs' => [
            'current' => 'Exames de Glicose',
        ],
        'chart' => [
            'title' => 'Glicose',
            'description' => 'Confira seus 5 resultados mais recentes de glicose e veja como seus níveis mudaram ao longo do tempo.',
            'footer_total_label' => 'Total de registros de glicose:',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Data do exame',
                'glucose_level' => 'Nível de glicose',
                'glycated_hemoglobin' => 'Hemoglobina glicada',
                'estimated_average_glucose' => 'Glicose média estimada',
                'created_at' => 'Criado em',
            ],
            'menu' => [
                'open_label' => 'Abrir menu',
                'copy_fields' => 'Copiar campos',
                'copy_values' => 'Copiar valores',
                'actions' => 'Ações',
                'edit' => 'Editar',
                'delete' => 'Excluir',
            ],
            'copy_values_content' => 'Data do exame: {report_date}, Nível de glicose: {glucose_level} {unit}, Hemoglobina glicada: {glycated_hemoglobin}{percentage_unit}, Glicose média estimada: {estimated_average_glucose} {unit}, Criado em: {created_at}',
            'delete_dialog' => [
                'title' => 'Excluir registro',
                'description' => 'Tem certeza de que deseja excluir este registro? Esta ação não pode ser desfeita.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Criar registro de glicose',
        'title' => 'Criar registro de glicose',
        'description' => 'Cadastre um novo resultado de glicose para você',
        'breadcrumbs' => [
            'index' => 'Glicose',
            'current' => 'Criar',
        ],
        'footer' => 'Ao criar um resultado de glicose, você pode acompanhar seus níveis de açúcar no sangue ao longo do tempo e obter percepções sobre sua saúde geral. O monitoramento regular dos seus níveis de glicose pode ajudar você a tomar decisões mais informadas sobre seu estilo de vida e seus cuidados com a saúde.',
    ],
    'edit' => [
        'head_title' => 'Editar registro de glicose',
        'title' => 'Editar registro de glicose',
        'description' => 'Atualize seu resultado de glicose para manter seus registros de saúde corretos e atualizados',
        'breadcrumbs' => [
            'index' => 'Glicose',
            'current' => 'Editar',
        ],
        'footer' => 'Ao editar um resultado de glicose, você pode acompanhar seus níveis de açúcar no sangue ao longo do tempo e obter percepções sobre sua saúde geral. O monitoramento regular dos seus níveis de glicose pode ajudar você a tomar decisões mais informadas sobre seu estilo de vida e seus cuidados com a saúde.',
    ],
    'form' => [
        'glucose_level' => 'Nível de glicose',
        'glucose_level_placeholder' => 'ex.: 87',
        'glycated_hemoglobin' => 'Hemoglobina glicada (HbA1c)',
        'glycated_hemoglobin_placeholder' => 'ex.: 4,9',
        'estimated_average_glucose' => 'Glicose média estimada (eAG)',
        'estimated_average_glucose_placeholder' => 'ex.: 99',
        'report_date' => 'Data do exame',
        'submit' => 'Salvar',
    ],
];
