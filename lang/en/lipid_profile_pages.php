<?php

return [
    'shared' => [
        'unit' => 'mg/dL',
        'back' => 'Back',
        'back_label' => 'Go Back',
    ],
    'index' => [
        'head_title' => 'Lipid Profile Exams',
        'title' => 'Lipid Profile Exams',
        'description' => 'View and analyze your lipid profile results.',
        'breadcrumbs' => [
            'current' => 'Lipid Profile Exams',
        ],
        'chart' => [
            'title' => 'Lipid Profile',
            'description' => 'Check your last 5 lipid profile results and see how your cholesterol levels have changed over time.',
            'footer_total_label' => 'Total lipid profile readings:',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Report Date',
                'total_cholesterol' => 'Total Cholesterol',
                'hdl_cholesterol' => 'HDL Cholesterol',
                'ldl_cholesterol' => 'LDL Cholesterol',
                'vldl_cholesterol' => 'VLDL Cholesterol',
                'triglycerides' => 'Triglycerides',
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
            'copy_values_content' => 'Report Date: {report_date}, Total Cholesterol: {total_cholesterol} {unit}, HDL Cholesterol: {hdl_cholesterol} {unit}, LDL Cholesterol: {ldl_cholesterol} {unit}, VLDL Cholesterol: {vldl_cholesterol} {unit}, Triglycerides: {triglycerides} {unit}, Created At: {created_at}',
            'delete_dialog' => [
                'title' => 'Delete Record',
                'description' => 'Are you sure you want to delete this record? This action cannot be undone.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Create Lipid Profile record',
        'title' => 'Create Lipid Profile record',
        'description' => 'Register a new lipid profile result for yourself',
        'breadcrumbs' => [
            'index' => 'Lipid Profile',
            'current' => 'Create',
        ],
        'footer' => 'By creating a lipid profile record, you can track your cholesterol levels over time and gain insights into your cardiovascular health. Regular monitoring of your lipid profile can help you make informed decisions about your lifestyle and healthcare.',
    ],
    'edit' => [
        'head_title' => 'Edit Lipid Profile record',
        'title' => 'Edit Lipid Profile record',
        'description' => 'Update an existing lipid profile result',
        'breadcrumbs' => [
            'index' => 'Lipid Profile',
            'current' => 'Edit',
        ],
        'footer' => 'By editing a lipid profile record, you can update your cholesterol levels over time and gain insights into your cardiovascular health. Regular monitoring of your lipid profile can help you make informed decisions about your lifestyle and healthcare.',
    ],
    'form' => [
        'total_cholesterol' => 'Total Cholesterol',
        'total_cholesterol_placeholder' => 'e.g. 175',
        'hdl_cholesterol' => 'HDL Cholesterol',
        'hdl_cholesterol_placeholder' => 'e.g. 50',
        'ldl_cholesterol' => 'LDL Cholesterol',
        'ldl_cholesterol_placeholder' => 'e.g. 100',
        'vldl_cholesterol' => 'VLDL Cholesterol',
        'vldl_cholesterol_placeholder' => 'e.g. 25',
        'triglycerides' => 'Triglycerides',
        'triglycerides_placeholder' => 'e.g. 150',
        'report_date' => 'Report Date',
        'submit' => 'Save',
    ],
];
