<?php

declare(strict_types=1);

return [
    'shared' => [
        'unit' => 'mg/dL',
        'percentage_unit' => '%',
        'back' => 'Back',
        'back_label' => 'Go Back',
    ],
    'index' => [
        'head_title' => 'Glucose Exams',
        'title' => 'Glucose Exams',
        'description' => 'View and analyze your glucose results.',
        'breadcrumbs' => [
            'current' => 'Glucose Exams',
        ],
        'chart' => [
            'title' => 'Glucose',
            'description' => 'Check your last 5 glucose results and see how your levels have changed over time.',
            'footer_total_label' => 'Total glucose readings:',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Report Date',
                'glucose_level' => 'Glucose Level',
                'glycated_hemoglobin' => 'Glycated Hemoglobin',
                'estimated_average_glucose' => 'Estimated Average Glucose',
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
            'copy_values_content' => 'Report Date: {report_date}, Glucose Level: {glucose_level} {unit}, Glycated Hemoglobin: {glycated_hemoglobin}{percentage_unit}, Estimated Average Glucose: {estimated_average_glucose} {unit}, Created At: {created_at}',
            'delete_dialog' => [
                'title' => 'Delete Record',
                'description' => 'Are you sure you want to delete this record? This action cannot be undone.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Create Glucose record',
        'title' => 'Create Glucose record',
        'description' => 'Register a new glucose result for yourself',
        'breadcrumbs' => [
            'index' => 'Glucose',
            'current' => 'Create',
        ],
        'footer' => 'By creating a glucose result, you can track your blood sugar levels over time and gain insights into your overall health. Regular monitoring of your glucose levels can help you make informed decisions about your lifestyle and healthcare.',
    ],
    'edit' => [
        'head_title' => 'Edit Glucose record',
        'title' => 'Edit Glucose record',
        'description' => 'Update your glucose result to keep your health records accurate and up-to-date',
        'breadcrumbs' => [
            'index' => 'Glucose',
            'current' => 'Edit',
        ],
        'footer' => 'By editing a glucose result, you can track your blood sugar levels over time and gain insights into your overall health. Regular monitoring of your glucose levels can help you make informed decisions about your lifestyle and healthcare.',
    ],
    'form' => [
        'glucose_level' => 'Glucose Level',
        'glucose_level_placeholder' => 'e.g. 87',
        'glycated_hemoglobin' => 'Glycated Hemoglobin (HbA1c)',
        'glycated_hemoglobin_placeholder' => 'e.g. 4.9',
        'estimated_average_glucose' => 'Estimated Average Glucose (eAG)',
        'estimated_average_glucose_placeholder' => 'e.g. 99',
        'report_date' => 'Report Date',
        'submit' => 'Save',
    ],
];
