<?php

declare(strict_types=1);

return [
    'shared' => [
        'unit' => 'ng/mL',
        'back' => 'Back',
        'back_label' => 'Go Back',
    ],
    'index' => [
        'head_title' => 'Vitamin D3 Exams',
        'title' => 'Vitamin D3 Exams',
        'description' => 'View and analyze your Vitamin D3 results.',
        'breadcrumbs' => [
            'current' => 'Vitamin D3 Exams',
        ],
        'chart' => [
            'title' => 'Vitamin D3',
            'description' => 'Check your last 5 Vitamin D3 results and see how your levels have changed over time.',
            'footer_total_label' => 'Total Vitamin D3 readings:',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Report Date',
                'vitamin_d3' => 'Vitamin D3',
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
            'copy_values_content' => 'Report Date: {report_date}, Vitamin D3: {vitamin_d3} {unit}, Created At: {created_at}',
            'delete_dialog' => [
                'title' => 'Delete Record',
                'description' => 'Are you sure you want to delete this record? This action cannot be undone.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Create Vitamin D3 record',
        'title' => 'Create Vitamin D3 record',
        'description' => 'Register a new Vitamin D3 result for yourself',
        'breadcrumbs' => [
            'index' => 'Vitamin D3',
            'current' => 'Create',
        ],
        'footer' => 'By creating a Vitamin D3 record, you can track your vitamin D levels over time and gain insights into your overall health. Regular monitoring of your vitamin D levels can help you make informed decisions about your lifestyle and healthcare.',
    ],
    'edit' => [
        'head_title' => 'Edit Vitamin D3 record',
        'title' => 'Edit Vitamin D3 record',
        'description' => 'Update an existing Vitamin D3 result',
        'breadcrumbs' => [
            'index' => 'Vitamin D3',
            'current' => 'Edit',
        ],
        'footer' => 'By editing a Vitamin D3 record, you can update your vitamin D levels over time and gain insights into your overall health. Regular monitoring of your vitamin D levels can help you make informed decisions about your lifestyle and healthcare.',
    ],
    'form' => [
        'vitamin_d3' => 'Vitamin D3',
        'vitamin_d3_placeholder' => 'e.g. 30',
        'report_date' => 'Report Date',
        'submit' => 'Save',
    ],
];
