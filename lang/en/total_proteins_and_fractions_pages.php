<?php

declare(strict_types=1);

return [
    'shared' => [
        'unit' => 'g/dL',
        'back' => 'Back',
        'back_label' => 'Go Back',
    ],
    'index' => [
        'head_title' => 'Total Proteins and Fractions Exams',
        'title' => 'Total Proteins and Fractions Exams',
        'description' => 'View and analyze your total proteins and fractions results.',
        'breadcrumbs' => [
            'current' => 'Total Proteins and Fractions Exams',
        ],
        'chart' => [
            'title' => 'Total Proteins and Fractions',
            'description' => 'Check your last 5 total proteins and fractions results and see how your protein levels have changed over time.',
            'footer_total_label' => 'Total total proteins and fractions readings:',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Report Date',
                'total_proteins' => 'Total Proteins',
                'albumin' => 'Albumin',
                'globulin' => 'Globulin',
                'albumin_globulin_ratio' => 'Albumin/Globulin Ratio',
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
            'copy_values_content' => 'Report Date: {report_date}, Total Proteins: {total_proteins} {unit}, Albumin: {albumin} {unit}, Globulin: {globulin} {unit}, Albumin/Globulin Ratio: {albumin_globulin_ratio}, Created At: {created_at}',
            'delete_dialog' => [
                'title' => 'Delete Record',
                'description' => 'Are you sure you want to delete this record? This action cannot be undone.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Create Total Proteins and Fractions record',
        'title' => 'Create Total Proteins and Fractions record',
        'description' => 'Register a new total proteins and fractions result for yourself',
        'breadcrumbs' => [
            'index' => 'Total Proteins and Fractions',
            'current' => 'Create',
        ],
        'footer' => 'By creating a total proteins and fractions record, you can track your protein levels over time and gain insights into your overall health. Regular monitoring of your total proteins and fractions can help you make informed decisions about your lifestyle and healthcare.',
    ],
    'edit' => [
        'head_title' => 'Edit Total Proteins and Fractions record',
        'title' => 'Edit Total Proteins and Fractions record',
        'description' => 'Update your total proteins and fractions result',
        'breadcrumbs' => [
            'index' => 'Total Proteins and Fractions',
            'current' => 'Edit',
        ],
        'footer' => 'By updating your total proteins and fractions record, you can keep track of your protein levels over time and gain insights into your overall health. Regular monitoring of your total proteins and fractions can help you make informed decisions about your lifestyle and healthcare.',
    ],
    'form' => [
        'total_proteins' => 'Total Proteins',
        'total_proteins_placeholder' => 'e.g. 7.2',
        'albumin' => 'Albumin',
        'albumin_placeholder' => 'e.g. 4.5',
        'globulin' => 'Globulin',
        'globulin_placeholder' => 'e.g. 2.7',
        'report_date' => 'Report Date',
        'submit' => 'Submit',
    ],
];
