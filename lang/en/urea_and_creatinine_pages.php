<?php

declare(strict_types=1);

return [
    'shared' => [
        'unit' => 'mg/dL',
        'back' => 'Back',
        'back_label' => 'Go Back',
    ],
    'index' => [
        'head_title' => 'Urea and Creatinine Exams',
        'title' => 'Urea and Creatinine Exams',
        'description' => 'View and analyze your urea and creatinine results.',
        'breadcrumbs' => [
            'current' => 'Urea and Creatinine Exams',
        ],
        'chart' => [
            'title' => 'Urea and Creatinine',
            'description' => 'Check your last 5 urea and creatinine results and see how your levels have changed over time.',
            'footer_total_label' => 'Total urea and creatinine readings:',
            'datasets' => [
                'urea_to_creatinine_ratio' => 'Urea to Creatinine Ratio',
            ],
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Report Date',
                'urea_level' => 'Urea Level',
                'creatinine_level' => 'Creatinine Level',
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
            'copy_values_content' => 'Report Date: {report_date}, Urea Level: {urea_level} {unit}, Creatinine Level: {creatinine_level} {unit}, Created At: {created_at}',
            'delete_dialog' => [
                'title' => 'Delete Record',
                'description' => 'Are you sure you want to delete this record? This action cannot be undone.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Create Urea and Creatinine record',
        'title' => 'Create Urea and Creatinine record',
        'description' => 'Register a new urea and creatinine result for yourself',
        'breadcrumbs' => [
            'index' => 'Urea and Creatinine',
            'current' => 'Create',
        ],
        'footer' => 'By creating a urea and creatinine record, you can track your kidney function over time and gain insights into your overall health. Regular monitoring of your kidney function can help you make informed decisions about your lifestyle and healthcare.',
    ],
    'edit' => [
        'head_title' => 'Edit Urea and Creatinine record',
        'title' => 'Edit Urea and Creatinine record',
        'description' => 'Update an existing urea and creatinine result',
        'breadcrumbs' => [
            'index' => 'Urea and Creatinine',
            'current' => 'Edit',
        ],
        'footer' => 'By editing a urea and creatinine record, you can update your kidney function history over time and gain insights into your overall health. Regular monitoring of your kidney function can help you make informed decisions about your lifestyle and healthcare.',
    ],
    'form' => [
        'urea_level' => 'Urea Level',
        'urea_level_placeholder' => 'e.g. 40',
        'creatinine_level' => 'Creatinine Level',
        'creatinine_level_placeholder' => 'e.g. 1.2',
        'report_date' => 'Report Date',
        'submit' => 'Save',
    ],
];
