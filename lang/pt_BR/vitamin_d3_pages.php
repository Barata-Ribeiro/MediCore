<?php

return [
    'shared' => [
        'unit' => 'ng/mL',
        'back' => 'Voltar',
        'back_label' => 'Voltar',
    ],
    'index' => [
        'head_title' => 'Exames de Vitamina D3',
        'title' => 'Exames de Vitamina D3',
        'description' => 'Visualize e analise seus resultados de vitamina D3.',
        'breadcrumbs' => [
            'current' => 'Exames de Vitamina D3',
        ],
        'chart' => [
            'title' => 'Vitamina D3',
            'description' => 'Confira seus 5 resultados mais recentes de vitamina D3 e veja como seus níveis mudaram ao longo do tempo.',
            'footer_total_label' => 'Total de registros de vitamina D3:',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Data do exame',
                'vitamin_d3' => 'Vitamina D3',
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
            'copy_values_content' => 'Data do exame: {report_date}, Vitamina D3: {vitamin_d3} {unit}, Criado em: {created_at}',
            'delete_dialog' => [
                'title' => 'Excluir registro',
                'description' => 'Tem certeza de que deseja excluir este registro? Esta ação não pode ser desfeita.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Criar registro de vitamina D3',
        'title' => 'Criar registro de vitamina D3',
        'description' => 'Cadastre um novo resultado de vitamina D3 para você',
        'breadcrumbs' => [
            'index' => 'Vitamina D3',
            'current' => 'Criar',
        ],
        'footer' => 'Ao criar um registro de vitamina D3, você pode acompanhar seus níveis ao longo do tempo e obter percepções sobre sua saúde geral. O monitoramento regular pode ajudar você a tomar decisões mais informadas sobre seu estilo de vida e seus cuidados com a saúde.',
    ],
    'edit' => [
        'head_title' => 'Editar registro de vitamina D3',
        'title' => 'Editar registro de vitamina D3',
        'description' => 'Atualize um resultado existente de vitamina D3',
        'breadcrumbs' => [
            'index' => 'Vitamina D3',
            'current' => 'Editar',
        ],
        'footer' => 'Ao editar um registro de vitamina D3, você pode atualizar seus níveis ao longo do tempo e obter percepções sobre sua saúde geral. O monitoramento regular pode ajudar você a tomar decisões mais informadas sobre seu estilo de vida e seus cuidados com a saúde.',
    ],
    'form' => [
        'vitamin_d3' => 'Vitamina D3',
        'vitamin_d3_placeholder' => 'ex.: 30',
        'report_date' => 'Data do exame',
        'submit' => 'Salvar',
    ],
];
