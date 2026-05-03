<?php

declare(strict_types=1);

return [
    'head_title' => 'Dashboard',
    'greeting' => [
        'morning' => 'Good morning',
        'afternoon' => 'Good afternoon',
        'evening' => 'Good evening',
        'message' => 'Here\'s a quick overview of your account and recent activity.',
    ],
    'profile_card' => [
        'empty' => [
            'title' => 'No profile information',
            'message' => 'Please complete your profile to access all features.',
            'action' => 'Complete Profile',
        ],
        'card' => [
            'title' => 'Profile Summary',
            'description' => 'A brief overview of your profile information.',
            'full_name' => 'Full Name',
            'sex' => 'Sex',
            'date_of_birth' => 'Date of Birth',
            'phone_number' => 'Phone Number',
            'address' => 'Address',
            'email' => 'Email',
            'updated_at' => 'Last Updated:',
            'edit_action' => 'Edit Profile',
        ],
    ],
    'bmi_card' => [
        'empty' => [
            'title' => 'No Height or Weight Information',
            'message' => 'It seems like your medical file is missing height and weight information, which are necessary to calculate your BMI',
            'action' => 'Update Medical File',
        ],
        'card' => [
            'title' => 'Body Mass Index (BMI)',
            'description' => 'Your BMI is a measure of body fat based on your height and weight.',
            'label' => 'BMI progress bar',
            'category' => [
                'underweight' => 'Underweight',
                'normal' => 'Normal weight',
                'overweight' => 'Overweight',
                'obesity_class_1' => 'Obesity Class I',
                'obesity_class_2' => 'Obesity Class II',
                'obesity_class_3' => 'Obesity Class III',
            ],
            'height' => 'Height',
            'weight' => 'Weight',
            'action' => 'Update Medical File',
        ],
    ],
    'medical_file_card' => [
        'empty' => [
            'title' => 'No Medical File Information',
            'message' => 'Please complete your medical file to access all features.',
            'action' => 'Complete Medical File',
        ],
        'card' => [
            'title' => 'Medical File Summary',
            'description' => 'A brief overview of your medical file information.',
            'blood_type' => 'Blood Type',
            'allergies' => 'Allergies',
            'diseases' => 'Diseases',
            'medications' => 'Medications',
            'updated_at' => 'Last Updated:',
            'edit_action' => 'Edit Medical File',
        ],
    ],
    'exams_summary_card' => [
        'title' => 'Recent Exams',
        'description' => 'A quick overview of your recent medical exams.',
        'recorded' => 'exams recorded',
    ],
    'exams_made_card' => [
        'title' => 'Exams Made',
        'description' => 'A chart showing the number of all exams made by you.',
        'made' => 'exams made',
        'chart_label' => 'Exams',
        'chart_empty' => 'No exams yet',
    ],
    'card_not_informed' => 'Not informed',
];
