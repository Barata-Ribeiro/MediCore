<?php

declare(strict_types=1);

return [
    'menu' => [
        'sidebar_group_label' => 'Platform',
        'sidebar_items' => [
            'dashboard' => 'Dashboard',
            'medical_file' => 'Medical File',
            'exams' => 'Exams',
            'exams_items' => [
                'complete_blood_count' => 'Complete Blood Count',
                'glucose' => 'Glucose',
                'lipid_profile' => 'Lipid Profile',
                'ultrasensitive_tsh' => 'Ultrasensitive TSH',
                'urea_and_creatinine' => 'Urea and Creatinine',
                'vitamin_b12' => 'Vitamin B12',
                'vitamin_d3' => 'Vitamin D3',
            ],
            'footer_items' => [
                'repository' => 'Repository',
                'made_by' => 'Made by Barata Ribeiro',
            ],
        ],
        'user_dropdown' => [
            'settings' => 'Settings',
            'logout' => 'Log out',
        ],
    ],
    'command_bar' => [
        'placeholder' => 'Search...',
        'main_navigation' => 'Main Navigation',
        'search_placeholder' => 'Type a command or search...',
        'no_results' => 'No results found.',
        'settings_items' => [
            'profile' => 'Profile',
            'change_password' => 'Change Password',
            'appearance' => 'Appearance',
        ],
    ],
    'appearance_dropdown' => [
        'title' => 'Select Appearance',
        'light' => 'Light',
        'dark' => 'Dark',
        'system' => 'System',
    ],
    'settings_layout' => [
        'sidebar_items' => [
            'profile' => 'Profile',
            'medical_file' => 'Medical File',
            'security' => 'Security',
            'appearance' => 'Appearance',
        ],
        'title' => 'Settings',
        'description' => 'Manage your profile and account settings',
    ],
    'chart_empty_state' => [
        'title' => 'No Data',
        'description' => 'No data available to display the chart. Please add some data to visualize your insights.',
        'action_label' => 'Create new record of this type',
        'action' => 'Create',
    ],
    'data_table' => [
        'column_header' => [
            'asc' => 'Ascending',
            'desc' => 'Descending',
            'clear' => 'Clear sorting',
            'hide' => 'Hide column',
        ],
        'toolbar' => [
            'search' => [
                'flash_error' => 'Failed to perform search. Please try again.',
                'placeholder' => 'Type to search...',
                'action' => 'Search',
            ],
            'faceted_filter' => [
                'badge_selected_suffix' => 'selected',
                'empty_results' => 'No results found.',
                'clear_titled_action' => 'Clear {title} filter',
                'clear_action' => 'Clear filters',
            ],
            'eraser_label' => 'Clear filters',
        ],
        'create_record' => [
            'label' => 'Create new record of this type',
            'action' => 'Create',
        ],
        'export_record' => [
            'label' => 'Choose how to export data',
            'action' => 'Export',
            'dropdown_label' => 'Exportables',
            'csv_label' => 'Export as CSV',
            'pdf_label' => 'Export as PDF',
        ],
        'empty_message' => 'No records found.',
        'pagination' => [
            'info' => 'Showing {from} to {to} of {total} results',
            'per_page_label' => 'Rows per page',
            'first_page_label' => 'Go to first page',
            'previous_page_label' => 'Go to previous page',
            'next_page_label' => 'Go to next page',
            'last_page_label' => 'Go to last page',
        ],
    ],
];
