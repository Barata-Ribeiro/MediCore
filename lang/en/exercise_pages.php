<?php

declare(strict_types=1);

return [
    'index' => [
        'head_title' => 'Exercises',
        'title' => 'Exercise Catalog',
        'description' => 'Manage your personal exercise catalog used by workouts.',
        'breadcrumbs' => [
            'current' => 'Exercises',
        ],
        'table' => [
            'columns' => [
                'id' => 'ID',
                'name' => 'Name',
                'muscle_groups' => 'Muscle groups',
                'video' => 'Video',
                'created_at' => 'Created At',
            ],
            'open_video' => 'Open video',
            'menu' => [
                'open_label' => 'Open menu',
                'actions' => 'Actions',
                'edit' => 'Edit',
                'delete' => 'Delete',
            ],
            'delete_dialog' => [
                'title' => 'Delete Exercise',
                'description' => 'Are you sure you want to delete this exercise? This action cannot be undone.',
            ],
        ],
    ],
    'form' => [
        'title' => 'Exercise editor',
        'description' => 'Create or update exercises linked to your own muscle groups.',
        'name' => 'Exercise name',
        'name_placeholder' => 'e.g. Bench Press',
        'description_field' => 'Description',
        'description_placeholder' => 'Optional coaching notes and setup instructions.',
        'video_url' => 'Video URL',
        'video_url_placeholder' => 'https://',
        'muscle_groups' => 'Muscle groups',
        'muscle_groups_hint' => 'Select one or more muscle groups trained by this exercise.',
        'create_action' => 'Add exercise',
        'update_action' => 'Update exercise',
        'cancel_action' => 'Cancel edit',
    ],
];
