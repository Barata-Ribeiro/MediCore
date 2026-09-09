<?php

declare(strict_types=1);

return [
    'shared' => [
        'unit' => 'U/L',
        'back' => 'Back',
        'back_label' => 'Go Back',
    ],
    'index' => [
        'head_title' => 'TGO and TGP Exams',
        'title' => 'TGO and TGP Exams',
        'description' => 'View and analyze your TGO and TGP results.',
        'breadcrumbs' => [
            'current' => 'TGO and TGP Exams',
        ],
        'chart' => [
            'title' => 'TGO and TGP',
            'description' => 'Daily averages for your 5 most recent report dates.',
            'footer_total_label' => 'Total TGO and TGP readings:',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Report Date',
                'tgo_level' => 'Aspartate Aminotransferase - TGO (AST)',
                'tgp_level' => 'Alanine Aminotransferase - TGP (ALT)',
                'created_at' => 'Created At',
            ],
            'menu' => [
                'open_label' => 'Open menu',
                'copy_fields' => 'Copy Fields',
                'copy_values' => 'Copy Values',
                'actions' => 'Actions',
                'edit' => 'Edit',
                'delete' => 'Delete',
            ],
            'copy_values_content' => 'Report Date: {report_date}, Aspartate Aminotransferase - TGO (AST): {tgo_level} {unit}, Alanine Aminotransferase - TGP (ALT): {tgp_level} {unit}, Created At: {created_at}',
            'delete_dialog' => [
                'title' => 'Delete Record',
                'description' => 'Are you sure you want to delete this record? This action cannot be undone.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Create TGO and TGP record',
        'title' => 'Create TGO and TGP record',
        'description' => 'Register a new TGO and TGP result for yourself',
        'breadcrumbs' => [
            'index' => 'TGO and TGP',
            'current' => 'Create',
        ],
        'footer' => 'Keep your TGO and TGP results together to track your exam history.',
    ],
    'edit' => [
        'head_title' => 'Edit TGO and TGP record',
        'title' => 'Edit TGO and TGP record',
        'description' => 'Update an existing TGO and TGP result',
        'breadcrumbs' => [
            'index' => 'TGO and TGP',
            'current' => 'Edit',
        ],
        'footer' => 'Keep your TGO and TGP results together to track your exam history.',
    ],
    'form' => [
        'tgo_level' => 'Aspartate Aminotransferase - TGO (AST)',
        'tgo_level_placeholder' => 'e.g. 40',
        'tgp_level' => 'Alanine Aminotransferase - TGP (ALT)',
        'tgp_level_placeholder' => 'e.g. 25',
        'report_date' => 'Report Date',
        'submit' => 'Save',
    ],
];
