<?php

return [
    'shared' => [
        'unit' => 'pg/mL',
        'back' => 'Back',
        'back_label' => 'Go Back',
    ],
    'index' => [
        'head_title' => 'Vitamin B12 Exams',
        'title' => 'Vitamin B12 Exams',
        'description' => 'View and analyze your Vitamin B12 results.',
        'breadcrumbs' => [
            'current' => 'Vitamin B12 Exams',
        ],
        'chart' => [
            'title' => 'Vitamin B12',
            'description' => 'Check your last 5 Vitamin B12 results and see how your levels have changed over time.',
            'footer_total_label' => 'Total Vitamin B12 readings:',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Report Date',
                'vitamin_b12' => 'Vitamin B12',
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
            'copy_values_content' => 'Report Date: {report_date}, Vitamin B12: {vitamin_b12} {unit}, Created At: {created_at}',
            'delete_dialog' => [
                'title' => 'Delete Record',
                'description' => 'Are you sure you want to delete this record? This action cannot be undone.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Create Vitamin B12 record',
        'title' => 'Create Vitamin B12 record',
        'description' => 'Register a new Vitamin B12 result for yourself',
        'breadcrumbs' => [
            'index' => 'Vitamin B12',
            'current' => 'Create',
        ],
        'footer' => 'By creating a Vitamin B12 record, you can track your vitamin B12 levels over time and gain insights into your overall health. Regular monitoring of your vitamin B12 levels can help you make informed decisions about your lifestyle and healthcare.',
    ],
    'edit' => [
        'head_title' => 'Edit Vitamin B12 record',
        'title' => 'Edit Vitamin B12 record',
        'description' => 'Update an existing Vitamin B12 result',
        'breadcrumbs' => [
            'index' => 'Vitamin B12',
            'current' => 'Edit',
        ],
        'footer' => 'By editing a Vitamin B12 record, you can update your vitamin B12 levels over time and gain insights into your overall health. Regular monitoring of your vitamin B12 levels can help you make informed decisions about your lifestyle and healthcare.',
    ],
    'form' => [
        'vitamin_b12' => 'Vitamin B12',
        'vitamin_b12_placeholder' => 'e.g. 500',
        'report_date' => 'Report Date',
        'submit' => 'Save',
    ],
];
