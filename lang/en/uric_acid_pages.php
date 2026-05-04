<?php

declare(strict_types=1);

return [
    'shared' => [
        'unit' => 'mg/dL',
        'back' => 'Back',
        'back_label' => 'Go Back',
    ],
    'index' => [
        'head_title' => 'Uric Acid Exams',
        'title' => 'Uric Acid Exams',
        'description' => 'View and analyze your uric acid results.',
        'breadcrumbs' => [
            'current' => 'Uric Acid Exams',
        ],
        'chart' => [
            'title' => 'Uric Acid',
            'description' => 'Check your last 5 uric acid results and see how your levels have changed over time.',
            'footer_total_label' => 'Total uric acid readings:',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Report Date',
                'uric_acid_level' => 'Uric Acid Level',
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
            'copy_values_content' => 'Report Date: {report_date}, Uric Acid Level: {uric_acid_level} {unit}, Created At: {created_at}',
            'delete_dialog' => [
                'title' => 'Delete Record',
                'description' => 'Are you sure you want to delete this record? This action cannot be undone.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Create Uric Acid record',
        'title' => 'Create Uric Acid record',
        'description' => 'Register a new uric acid result for yourself',
        'breadcrumbs' => [
            'index' => 'Uric Acid',
            'current' => 'Create',
        ],
        'footer' => 'By creating a uric acid record, you can track your uric acid levels over time and gain insights into your overall health. Regular monitoring can help you make informed decisions about your lifestyle and healthcare.',
    ],
    'edit' => [
        'head_title' => 'Edit Uric Acid record',
        'title' => 'Edit Uric Acid record',
        'description' => 'Update an existing uric acid result',
        'breadcrumbs' => [
            'index' => 'Uric Acid',
            'current' => 'Edit',
        ],
        'footer' => 'By editing a uric acid record, you can keep your uric acid history accurate and easier to review over time.',
    ],
    'form' => [
        'uric_acid_level' => 'Uric Acid Level',
        'uric_acid_level_placeholder' => 'e.g. 5.5',
        'report_date' => 'Report Date',
        'submit' => 'Save',
    ],
];
