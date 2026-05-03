<?php

declare(strict_types=1);

return [
    'shared' => [
        'unit' => 'mg/dL',
        'back' => 'Voltar',
        'back_label' => 'Voltar',
    ],
    'index' => [
        'head_title' => 'Exames de Ureia e Creatinina',
        'title' => 'Exames de Ureia e Creatinina',
        'description' => 'Visualize e analise seus resultados de ureia e creatinina.',
        'breadcrumbs' => [
            'current' => 'Exames de Ureia e Creatinina',
        ],
        'chart' => [
            'title' => 'Ureia e Creatinina',
            'description' => 'Confira seus 5 resultados mais recentes de ureia e creatinina e veja como seus níveis mudaram ao longo do tempo.',
            'footer_total_label' => 'Total de registros de ureia e creatinina:',
            'datasets' => [
                'urea_to_creatinine_ratio' => 'Relação ureia/creatinina',
            ],
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Data do exame',
                'urea_level' => 'Nível de ureia',
                'creatinine_level' => 'Nível de creatinina',
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
            'copy_values_content' => 'Data do exame: {report_date}, Nível de ureia: {urea_level} {unit}, Nível de creatinina: {creatinine_level} {unit}, Criado em: {created_at}',
            'delete_dialog' => [
                'title' => 'Excluir registro',
                'description' => 'Tem certeza de que deseja excluir este registro? Esta ação não pode ser desfeita.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Criar registro de ureia e creatinina',
        'title' => 'Criar registro de ureia e creatinina',
        'description' => 'Cadastre um novo resultado de ureia e creatinina para você',
        'breadcrumbs' => [
            'index' => 'Ureia e Creatinina',
            'current' => 'Criar',
        ],
        'footer' => 'Ao criar um registro de ureia e creatinina, você pode acompanhar sua função renal ao longo do tempo e obter percepções sobre sua saúde geral. O monitoramento regular da sua função renal pode ajudar você a tomar decisões mais informadas sobre seu estilo de vida e seus cuidados com a saúde.',
    ],
    'edit' => [
        'head_title' => 'Editar registro de ureia e creatinina',
        'title' => 'Editar registro de ureia e creatinina',
        'description' => 'Atualize um resultado existente de ureia e creatinina',
        'breadcrumbs' => [
            'index' => 'Ureia e Creatinina',
            'current' => 'Editar',
        ],
        'footer' => 'Ao editar um registro de ureia e creatinina, você pode manter seu histórico da função renal atualizado ao longo do tempo e obter percepções sobre sua saúde geral. O monitoramento regular da sua função renal pode ajudar você a tomar decisões mais informadas sobre seu estilo de vida e seus cuidados com a saúde.',
    ],
    'form' => [
        'urea_level' => 'Nível de ureia',
        'urea_level_placeholder' => 'ex.: 40',
        'creatinine_level' => 'Nível de creatinina',
        'creatinine_level_placeholder' => 'ex.: 1,2',
        'report_date' => 'Data do exame',
        'submit' => 'Salvar',
    ],
];
