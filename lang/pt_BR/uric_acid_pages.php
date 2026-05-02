<?php

return [
    'shared' => [
        'unit' => 'mg/dL',
        'back' => 'Voltar',
        'back_label' => 'Voltar',
    ],
    'index' => [
        'head_title' => 'Exames de Ácido Úrico',
        'title' => 'Exames de Ácido Úrico',
        'description' => 'Visualize e analise seus resultados de ácido úrico.',
        'breadcrumbs' => [
            'current' => 'Exames de Ácido Úrico',
        ],
        'chart' => [
            'title' => 'Ácido Úrico',
            'description' => 'Confira seus 5 resultados mais recentes de ácido úrico e veja como seus níveis mudaram ao longo do tempo.',
            'footer_total_label' => 'Total de registros de ácido úrico:',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Data do exame',
                'uric_acid_level' => 'Nível de ácido úrico',
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
            'copy_values_content' => 'Data do exame: {report_date}, Nível de ácido úrico: {uric_acid_level} {unit}, Criado em: {created_at}',
            'delete_dialog' => [
                'title' => 'Excluir registro',
                'description' => 'Tem certeza de que deseja excluir este registro? Esta ação não pode ser desfeita.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Criar registro de ácido úrico',
        'title' => 'Criar registro de ácido úrico',
        'description' => 'Cadastre um novo resultado de ácido úrico para você',
        'breadcrumbs' => [
            'index' => 'Ácido Úrico',
            'current' => 'Criar',
        ],
        'footer' => 'Ao criar um registro de ácido úrico, você pode acompanhar seus níveis ao longo do tempo e obter percepções sobre sua saúde geral. O monitoramento regular pode ajudar você a tomar decisões mais informadas sobre seu estilo de vida e seus cuidados com a saúde.',
    ],
    'edit' => [
        'head_title' => 'Editar registro de ácido úrico',
        'title' => 'Editar registro de ácido úrico',
        'description' => 'Atualize um resultado existente de ácido úrico',
        'breadcrumbs' => [
            'index' => 'Ácido Úrico',
            'current' => 'Editar',
        ],
        'footer' => 'Ao editar um registro de ácido úrico, você pode manter seu histórico preciso e mais fácil de revisar ao longo do tempo.',
    ],
    'form' => [
        'uric_acid_level' => 'Nível de ácido úrico',
        'uric_acid_level_placeholder' => 'ex.: 5,5',
        'report_date' => 'Data do exame',
        'submit' => 'Salvar',
    ],
];
