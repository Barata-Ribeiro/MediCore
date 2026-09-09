<?php

declare(strict_types=1);

return [
    'shared' => [
        'unit' => 'U/L',
        'back' => 'Voltar',
        'back_label' => 'Voltar',
    ],
    'index' => [
        'head_title' => 'Exames de TGO e TGP',
        'title' => 'Exames de TGO e TGP',
        'description' => 'Visualize e analise seus resultados de TGO e TGP.',
        'breadcrumbs' => [
            'current' => 'Exames de TGO e TGP',
        ],
        'chart' => [
            'title' => 'TGO e TGP',
            'description' => 'Médias diárias das suas 5 datas de exame mais recentes.',
            'footer_total_label' => 'Total de registros de TGO e TGP:',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Data do exame',
                'tgo_level' => 'Aspartato Aminotransferase - TGO',
                'tgp_level' => 'Alanina Aminotransferase - TGP',
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
            'copy_values_content' => 'Data do exame: {report_date}, Aspartato Aminotransferase - TGO: {tgo_level} {unit}, Alanina Aminotransferase - TGP: {tgp_level} {unit}, Criado em: {created_at}',
            'delete_dialog' => [
                'title' => 'Excluir registro',
                'description' => 'Tem certeza de que deseja excluir este registro? Esta ação não pode ser desfeita.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Criar registro de TGO e TGP',
        'title' => 'Criar registro de TGO e TGP',
        'description' => 'Cadastre um novo resultado de TGO e TGP para você',
        'breadcrumbs' => [
            'index' => 'TGO e TGP',
            'current' => 'Criar',
        ],
        'footer' => 'Mantenha seus resultados de TGO e TGP juntos para acompanhar seu histórico de exames.',
    ],
    'edit' => [
        'head_title' => 'Editar registro de TGO e TGP',
        'title' => 'Editar registro de TGO e TGP',
        'description' => 'Atualize um resultado existente de TGO e TGP',
        'breadcrumbs' => [
            'index' => 'TGO e TGP',
            'current' => 'Editar',
        ],
        'footer' => 'Mantenha seus resultados de TGO e TGP juntos para acompanhar seu histórico de exames.',
    ],
    'form' => [
        'tgo_level' => 'Aspartato Aminotransferase - TGO',
        'tgo_level_placeholder' => 'ex.: 40',
        'tgp_level' => 'Alanina Aminotransferase - TGP',
        'tgp_level_placeholder' => 'ex.: 25',
        'report_date' => 'Data do exame',
        'submit' => 'Salvar',
    ],
];
