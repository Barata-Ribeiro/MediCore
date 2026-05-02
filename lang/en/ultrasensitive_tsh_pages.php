<?php

return [
    'shared' => [
        'unit' => 'uIU/mL',
        'back' => 'Back',
        'back_label' => 'Go Back',
    ],
    'index' => [
        'head_title' => 'Ultrasensitive TSH Exams',
        'title' => 'Ultrasensitive TSH Exams',
        'description' => 'View and analyze your ultrasensitive TSH results.',
        'breadcrumbs' => [
            'current' => 'Ultrasensitive TSH Exams',
        ],
        'chart' => [
            'title' => 'Ultrasensitive TSH',
            'description' => 'Check your last 5 ultrasensitive TSH results and see how your levels have changed over time.',
            'footer_total_label' => 'Total ultrasensitive TSH readings:',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'report_date' => 'Report Date',
                'tsh_level' => 'Ultrasensitive TSH',
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
            'copy_values_content' => 'Report Date: {report_date}, Ultrasensitive TSH: {tsh_level} {unit}, Created At: {created_at}',
            'delete_dialog' => [
                'title' => 'Delete Record',
                'description' => 'Are you sure you want to delete this record? This action cannot be undone.',
            ],
        ],
    ],
    'create' => [
        'head_title' => 'Create Ultrasensitive TSH record',
        'title' => 'Create Ultrasensitive TSH record',
        'description' => 'Register a new ultrasensitive TSH result for yourself',
        'breadcrumbs' => [
            'index' => 'Ultrasensitive TSH',
            'current' => 'Create',
        ],
        'footer' => 'By creating an ultrasensitive TSH record, you can track your thyroid health over time and gain insights into your overall well-being. Regular monitoring of your ultrasensitive TSH levels can help you make informed decisions about your follow-up care.',
    ],
    'edit' => [
        'head_title' => 'Edit Ultrasensitive TSH record',
        'title' => 'Edit Ultrasensitive TSH record',
        'description' => 'Update an existing ultrasensitive TSH result',
        'breadcrumbs' => [
            'index' => 'Ultrasensitive TSH',
            'current' => 'Edit',
        ],
        'footer' => 'By editing an ultrasensitive TSH record, you can keep your thyroid history accurate over time and preserve a clearer view of your follow-up care.',
    ],
    'form' => [
        'tsh_level' => 'Ultrasensitive TSH',
        'tsh_level_placeholder' => 'e.g. 2.50',
        'report_date' => 'Report Date',
        'submit' => 'Save',
    ],
];
