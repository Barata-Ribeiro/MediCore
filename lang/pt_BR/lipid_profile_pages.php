<?php

declare(strict_types=1);

return [
    'shared' => [
        'unit' => 'mg/dL',
        'back' => 'Voltar',
        'back_label' => 'Voltar',
    ],
    'index' => [
        'head_title' => 'Exames de Perfil Lipídico',
        'title' => 'Exames de Perfil Lipídico',
        'description' => 'Visualize e analise seus resultados de perfil lipídico.',
        'breadcrumbs' => [
            'current' => 'Exames de Perfil Lipídico',
        ],
        'chart' => [
            'title' => 'Perfil Lipídico',
            'description' => 'Confira seus 5 resultados mais recentes de perfil lipídico e veja como seus níveis de colesterol mudaram ao longo do tempo.',
            'footer_total_label' => 'Total de registros de perfil lipídico:',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Data do exame',
                'total_cholesterol' => 'Colesterol Total',
                'hdl_cholesterol' => 'Colesterol HDL',
                'ldl_cholesterol' => 'Colesterol LDL',
                'vldl_cholesterol' => 'Colesterol VLDL',
                'triglycerides' => 'Triglicerídeos',
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
            'copy_values_content' => 'Data do exame: {report_date}, Colesterol Total: {total_cholesterol} {unit}, Colesterol HDL: {hdl_cholesterol} {unit}, Colesterol LDL: {ldl_cholesterol} {unit}, Colesterol VLDL: {vldl_cholesterol} {unit}, Triglicerídeos: {triglycerides} {unit}, Criado em: {created_at}',
            'delete_dialog' => [
                'title' => 'Excluir registro',
                'description' => 'Tem certeza de que deseja excluir este registro? Esta ação não pode ser desfeita.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Criar registro de perfil lipídico',
        'title' => 'Criar registro de perfil lipídico',
        'description' => 'Cadastre um novo resultado de perfil lipídico para você',
        'breadcrumbs' => [
            'index' => 'Perfil Lipídico',
            'current' => 'Criar',
        ],
        'footer' => 'Ao criar um registro de perfil lipídico, você pode acompanhar seus níveis de colesterol ao longo do tempo e obter percepções sobre sua saúde cardiovascular. O monitoramento regular do seu perfil lipídico pode ajudar você a tomar decisões mais informadas sobre seu estilo de vida e seus cuidados com a saúde.',
    ],
    'edit' => [
        'head_title' => 'Editar registro de perfil lipídico',
        'title' => 'Editar registro de perfil lipídico',
        'description' => 'Atualize um resultado existente de perfil lipídico',
        'breadcrumbs' => [
            'index' => 'Perfil Lipídico',
            'current' => 'Editar',
        ],
        'footer' => 'Ao editar um registro de perfil lipídico, você pode atualizar seus níveis de colesterol ao longo do tempo e obter percepções sobre sua saúde cardiovascular. O monitoramento regular do seu perfil lipídico pode ajudar você a tomar decisões mais informadas sobre seu estilo de vida e seus cuidados com a saúde.',
    ],
    'form' => [
        'total_cholesterol' => 'Colesterol Total',
        'total_cholesterol_placeholder' => 'ex.: 175',
        'hdl_cholesterol' => 'Colesterol HDL',
        'hdl_cholesterol_placeholder' => 'ex.: 50',
        'ldl_cholesterol' => 'Colesterol LDL',
        'ldl_cholesterol_placeholder' => 'ex.: 100',
        'vldl_cholesterol' => 'Colesterol VLDL',
        'vldl_cholesterol_placeholder' => 'ex.: 25',
        'triglycerides' => 'Triglicerídeos',
        'triglycerides_placeholder' => 'ex.: 150',
        'report_date' => 'Data do exame',
        'submit' => 'Salvar',
    ],
];
