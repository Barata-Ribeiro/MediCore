<?php

declare(strict_types=1);

return [
    'exams' => [
        'cbc' => [
            'store_successfully' => 'Complete blood count record created successfully.',
            'store_failed' => 'An error occurred while creating the complete blood count record. Please try again.',
            'update_unauthorized' => 'You are not authorized to update this complete blood count record.',
            'update_successfully' => 'Complete blood count record updated successfully.',
            'update_failed' => 'An error occurred while updating the complete blood count record. Please try again.',
            'destroy_unauthorized' => 'You are not authorized to delete this complete blood count record.',
            'destroy_successfully' => 'Complete blood count record deleted successfully.',
            'destroy_failed' => 'An error occurred while deleting the complete blood count record. Please try again.',
        ],
        'glucose' => [
            'store_successfully' => 'Glucose record created successfully.',
            'store_failed' => 'An error occurred while creating the glucose record. Please try again.',
            'update_unauthorized' => 'You are not authorized to update this glucose record.',
            'update_successfully' => 'Glucose record updated successfully.',
            'update_failed' => 'An error occurred while updating the glucose record. Please try again.',
            'destroy_unauthorized' => 'You are not authorized to delete this glucose record.',
            'destroy_successfully' => 'Glucose record deleted successfully.',
            'destroy_failed' => 'An error occurred while deleting the glucose record. Please try again.',
        ],
        'lipid_profile' => [
            'store_successfully' => 'Lipid profile record created successfully.',
            'store_failed' => 'An error occurred while creating the lipid profile record. Please try again.',
            'update_unauthorized' => 'You are not authorized to update this lipid profile record.',
            'update_successfully' => 'Lipid profile record updated successfully.',
            'update_failed' => 'An error occurred while updating the lipid profile record. Please try again.',
            'destroy_unauthorized' => 'You are not authorized to delete this lipid profile record.',
            'destroy_successfully' => 'Lipid profile record deleted successfully.',
            'destroy_failed' => 'An error occurred while deleting the lipid profile record. Please try again.',
        ],
        'ultrasensitive_tsh' => [
            'store_successfully' => 'Ultrasensitive TSH record created successfully.',
            'store_failed' => 'An error occurred while creating the ultrasensitive TSH record. Please try again.',
            'update_unauthorized' => 'You are not authorized to update this ultrasensitive TSH record.',
            'update_successfully' => 'Ultrasensitive TSH record updated successfully.',
            'update_failed' => 'An error occurred while updating the ultrasensitive TSH record. Please try again.',
            'destroy_unauthorized' => 'You are not authorized to delete this ultrasensitive TSH record.',
            'destroy_successfully' => 'Ultrasensitive TSH record deleted successfully.',
            'destroy_failed' => 'An error occurred while deleting the ultrasensitive TSH record. Please try again.',
        ],
        'urea_and_creatinine' => [
            'store_successfully' => 'Urea and creatinine record created successfully.',
            'store_failed' => 'An error occurred while creating the urea and creatinine record. Please try again.',
            'update_unauthorized' => 'You are not authorized to update this urea and creatinine record.',
            'update_successfully' => 'Urea and creatinine record updated successfully.',
            'update_failed' => 'An error occurred while updating the urea and creatinine record. Please try again.',
            'destroy_unauthorized' => 'You are not authorized to delete this urea and creatinine record.',
            'destroy_successfully' => 'Urea and creatinine record deleted successfully.',
            'destroy_failed' => 'An error occurred while deleting the urea and creatinine record. Please try again.',
        ],
        'uric_acid' => [
            'store_successfully' => 'Uric acid record created successfully.',
            'store_failed' => 'An error occurred while creating the uric acid record. Please try again.',
            'update_unauthorized' => 'You are not authorized to update this uric acid record.',
            'update_successfully' => 'Uric acid record updated successfully.',
            'update_failed' => 'An error occurred while updating the uric acid record. Please try again.',
            'destroy_unauthorized' => 'You are not authorized to delete this uric acid record.',
            'destroy_successfully' => 'Uric acid record deleted successfully.',
            'destroy_failed' => 'An error occurred while deleting the uric acid record. Please try again.',
        ],
        'vitamin_b12' => [
            'store_successfully' => 'Vitamin B12 record created successfully.',
            'store_failed' => 'An error occurred while creating the vitamin B12 record. Please try again.',
            'update_unauthorized' => 'You are not authorized to update this vitamin B12 record.',
            'update_successfully' => 'Vitamin B12 record updated successfully.',
            'update_failed' => 'An error occurred while updating the vitamin B12 record. Please try again.',
            'destroy_unauthorized' => 'You are not authorized to delete this vitamin B12 record.',
            'destroy_successfully' => 'Vitamin B12 record deleted successfully.',
            'destroy_failed' => 'An error occurred while deleting the vitamin B12 record. Please try again.',
        ],
        'vitamin_d3' => [
            'store_successfully' => 'Vitamin D3 record created successfully.',
            'store_failed' => 'An error occurred while creating the vitamin D3 record. Please try again.',
            'update_unauthorized' => 'You are not authorized to update this vitamin D3 record.',
            'update_successfully' => 'Vitamin D3 record updated successfully.',
            'update_failed' => 'An error occurred while updating the vitamin D3 record. Please try again.',
            'destroy_unauthorized' => 'You are not authorized to delete this vitamin D3 record.',
            'destroy_successfully' => 'Vitamin D3 record deleted successfully.',
            'destroy_failed' => 'An error occurred while deleting the vitamin D3 record. Please try again.',
        ],
    ],
    'settings' => [
        'medical_file' => [
            'updated_successfully' => 'Medical file updated successfully.',
            'failed_update' => 'An error occurred while updating the medical file.',
        ],
        'profile' => [
            'update' => [
                'updated_successfully' => 'Profile updated successfully.',
                'failed_update' => 'An error occurred while updating the profile.',
            ],
            'language' => [
                'updated_successfully' => 'Language updated successfully.',
                'failed_update' => 'An error occurred while updating the language.',
            ],
            'destroy' => [
                'deleted_successfully' => 'Profile deleted successfully.',
                'failed_delete' => 'An error occurred while deleting the profile.',
            ],
        ],
        'security' => [
            'update' => [
                'updated_successfully' => 'Password updated successfully.',
                'failed_update' => 'An error occurred while updating the password. Please try again.',
            ],
        ],
    ],
    'social_auth' => [
        'unsupported_provider' => 'Unsupported provider. Create an account using email and password instead.',
        'failed_redirect' => 'Failed to redirect to provider. Please try again.',
        'unsupported_provider_retry' => 'Unsupported provider. Please try again.',
        'successful_auth' => 'Successfully authenticated with :provider.',
        'failed_auth' => 'Failed to authenticate with :provider. Please try again.',
    ],
];
