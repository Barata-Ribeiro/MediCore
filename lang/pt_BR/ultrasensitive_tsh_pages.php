<?php

return [
    'shared' => [
        'unit' => 'uIU/mL',
        'back' => 'Voltar',
        'back_label' => 'Voltar',
    ],
    'index' => [
        'head_title' => 'Exames de TSH Ultrassensível',
        'title' => 'Exames de TSH Ultrassensível',
        'description' => 'Visualize e analise seus resultados de TSH ultrassensível.',
        'breadcrumbs' => [
            'current' => 'Exames de TSH Ultrassensível',
        ],
        'chart' => [
            'title' => 'TSH Ultrassensível',
            'description' => 'Confira seus 5 resultados mais recentes de TSH ultrassensível e veja como seus níveis mudaram ao longo do tempo.',
            'footer_total_label' => 'Total de registros de TSH ultrassensível:',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Data do exame',
                'tsh_level' => 'TSH Ultrassensível',
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
            'copy_values_content' => 'Data do exame: {report_date}, TSH ultrassensível: {tsh_level} {unit}, Criado em: {created_at}',
            'delete_dialog' => [
                'title' => 'Excluir registro',
                'description' => 'Tem certeza de que deseja excluir este registro? Esta ação não pode ser desfeita.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Criar registro de TSH ultrassensível',
        'title' => 'Criar registro de TSH ultrassensível',
        'description' => 'Cadastre um novo resultado de TSH ultrassensível para você',
        'breadcrumbs' => [
            'index' => 'TSH Ultrassensível',
            'current' => 'Criar',
        ],
        'footer' => 'Ao criar um registro de TSH ultrassensível, você pode acompanhar sua saúde da tireoide ao longo do tempo e obter percepções sobre seu bem-estar geral. O monitoramento regular dos seus níveis pode ajudar você a tomar decisões mais informadas sobre seu acompanhamento.',
    ],
    'edit' => [
        'head_title' => 'Editar registro de TSH ultrassensível',
        'title' => 'Editar registro de TSH ultrassensível',
        'description' => 'Atualize um resultado existente de TSH ultrassensível',
        'breadcrumbs' => [
            'index' => 'TSH Ultrassensível',
            'current' => 'Editar',
        ],
        'footer' => 'Ao editar um registro de TSH ultrassensível, você pode manter seu histórico da tireoide preciso ao longo do tempo e facilitar a revisão do seu acompanhamento.',
    ],
    'form' => [
        'tsh_level' => 'TSH Ultrassensível',
        'tsh_level_placeholder' => 'ex.: 2,50',
        'report_date' => 'Data do exame',
        'submit' => 'Salvar',
    ],
];
