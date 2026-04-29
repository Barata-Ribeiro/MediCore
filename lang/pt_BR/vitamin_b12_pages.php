<?php

return [
    'shared' => [
        'unit' => 'pg/mL',
        'back' => 'Voltar',
        'back_label' => 'Voltar',
    ],
    'index' => [
        'head_title' => 'Exames de Vitamina B12',
        'title' => 'Exames de Vitamina B12',
        'description' => 'Visualize e analise seus resultados de vitamina B12.',
        'breadcrumbs' => [
            'current' => 'Exames de Vitamina B12',
        ],
        'chart' => [
            'title' => 'Vitamina B12',
            'description' => 'Confira seus 5 resultados mais recentes de vitamina B12 e veja como seus níveis mudaram ao longo do tempo.',
            'footer_total_label' => 'Total de registros de vitamina B12:',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Data do exame',
                'vitamin_b12' => 'Vitamina B12',
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
            'copy_values_content' => 'Data do exame: {report_date}, Vitamina B12: {vitamin_b12} {unit}, Criado em: {created_at}',
            'delete_dialog' => [
                'title' => 'Excluir registro',
                'description' => 'Tem certeza de que deseja excluir este registro? Esta ação não pode ser desfeita.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Criar registro de vitamina B12',
        'title' => 'Criar registro de vitamina B12',
        'description' => 'Cadastre um novo resultado de vitamina B12 para você',
        'breadcrumbs' => [
            'index' => 'Vitamina B12',
            'current' => 'Criar',
        ],
        'footer' => 'Ao criar um registro de vitamina B12, você pode acompanhar seus níveis ao longo do tempo e obter percepções sobre sua saúde geral. O monitoramento regular pode ajudar você a tomar decisões mais informadas sobre seu estilo de vida e seus cuidados com a saúde.',
    ],
    'edit' => [
        'head_title' => 'Editar registro de vitamina B12',
        'title' => 'Editar registro de vitamina B12',
        'description' => 'Atualize um resultado existente de vitamina B12',
        'breadcrumbs' => [
            'index' => 'Vitamina B12',
            'current' => 'Editar',
        ],
        'footer' => 'Ao editar um registro de vitamina B12, você pode atualizar seus níveis ao longo do tempo e obter percepções sobre sua saúde geral. O monitoramento regular pode ajudar você a tomar decisões mais informadas sobre seu estilo de vida e seus cuidados com a saúde.',
    ],
    'form' => [
        'vitamin_b12' => 'Vitamina B12',
        'vitamin_b12_placeholder' => 'ex.: 500',
        'report_date' => 'Data do exame',
        'submit' => 'Salvar',
    ],
];
