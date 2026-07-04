<?php

declare(strict_types=1);

return [
    'shared' => [
        'unit' => 'g/dL',
        'back' => 'Voltar',
        'back_label' => 'Voltar',
    ],
    'index' => [
        'head_title' => 'Exames de Proteínas Totais e Frações',
        'title' => 'Exames de Proteínas Totais e Frações',
        'description' => 'Visualize e analise seus resultados de proteínas totais e frações.',
        'breadcrumbs' => [
            'current' => 'Exames de Proteínas Totais e Frações',
        ],
        'chart' => [
            'title' => 'Proteínas Totais e Frações',
            'description' => 'Confira seus últimos 5 resultados de proteínas totais e frações e veja como seus níveis de proteína mudaram ao longo do tempo.',
            'footer_total_label' => 'Total de registros de proteínas totais e frações:',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Data do Relatório',
                'total_proteins' => 'Proteínas Totais',
                'albumin' => 'Albumina',
                'globulin' => 'Globulina',
                'albumin_globulin_ratio' => 'Relação Albumina/Globulina',
                'created_at' => 'Criado Em',
            ],
            'menu' => [
                'open_label' => 'Abrir menu',
                'copy_fields' => 'Copiar Campos',
                'copy_values' => 'Copiar Valores',
                'actions' => 'Ações',
                'edit' => 'Editar',
                'delete' => 'Excluir',
            ],
            'copy_values_content' => 'Data do Exame: {report_date}, Proteínas Totais: {total_proteins} {unit}, Albumina: {albumin} {unit}, Globulina: {globulin} {unit}, Relação Albumina/Globulina: {albumin_globulin_ratio}, Criado Em: {created_at}',
            'delete_dialog' => [
                'title' => 'Excluir Registro',
                'description' => 'Tem certeza de que deseja excluir este registro? Esta ação não pode ser desfeita.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Criar Registro de Proteínas Totais e Frações',
        'title' => 'Criar Registro de Proteínas Totais e Frações',
        'description' => 'Registrar um novo resultado de proteínas totais e frações para você',
        'breadcrumbs' => [
            'index' => 'Proteínas Totais e Frações',
            'current' => 'Criar',
        ],
        'footer' => 'Ao criar um registro de proteínas totais e frações, você pode acompanhar seus níveis de proteínas ao longo do tempo e obter insights sobre sua saúde geral. O monitoramento regular de suas proteínas totais e frações pode ajudá-lo a tomar decisões informadas sobre seu estilo de vida e cuidados de saúde.',
    ],
    'edit' => [
        'head_title' => 'Editar Registro de Proteínas Totais e Frações',
        'title' => 'Editar Registro de Proteínas Totais e Frações',
        'description' => 'Atualizar seu resultado de proteínas totais e frações',
        'breadcrumbs' => [
            'index' => 'Proteínas Totais e Frações',
            'current' => 'Editar',
        ],
        'footer' => 'Ao atualizar seu registro de proteínas totais e frações, você pode acompanhar seus níveis de proteínas ao longo do tempo e obter insights sobre sua saúde geral. O monitoramento regular de suas proteínas totais e frações pode ajudá-lo a tomar decisões informadas sobre seu estilo de vida e cuidados de saúde.',
    ],
    'form' => [
        'total_proteins' => 'Proteínas Totais',
        'total_proteins_placeholder' => 'ex. 7.2',
        'albumin' => 'Albumina',
        'albumin_placeholder' => 'ex. 4.5',
        'globulin' => 'Globulina',
        'globulin_placeholder' => 'ex. 2.7',
        'report_date' => 'Data do exame',
        'submit' => 'Salvar',
    ],
];
