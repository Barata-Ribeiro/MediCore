<?php

declare(strict_types=1);

return [
    'index' => [
        'head_title' => 'Muscle Groups',
        'title' => 'Muscle Group Catalog',
        'description' => 'Manage your personal muscle groups for exercise classification.',
        'breadcrumbs' => [
            'current' => 'Muscle Groups',
        ],
        'table' => [
            'title' => 'Registered muscle groups',
            'description' => ':count muscle group(s) in your catalog.',
            'columns' => [
                'name' => 'Name',
                'exercises' => 'Linked exercises',
                'actions' => 'Actions',
            ],
            'edit' => 'Edit',
            'delete' => 'Delete',
        ],
    ],
    'form' => [
        'title' => 'Muscle group editor',
        'description' => 'Create or update your muscle groups used by exercises.',
        'name' => 'Muscle group name',
        'name_placeholder' => 'e.g. Pectorals',
        'create_action' => 'Add muscle group',
        'update_action' => 'Update muscle group',
        'cancel_action' => 'Cancel edit',
    ],
];
