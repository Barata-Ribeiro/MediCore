<?php

use Illuminate\Translation\Translator;

test('application translation keys resolve from standard language files', function () {
    $translator = app('translator');

    expect($translator)
        ->toBeInstanceOf(Translator::class)
        ->and($translator->get('flash.social_auth.unsupported_provider', [], 'en'))
        ->toBe('Unsupported provider. Create an account using email and password instead.')
        ->and($translator->get('flash.exams.urea_and_creatinine.destroy_successfully', [], 'en'))
        ->toBe('Urea and creatinine record deleted successfully.')
        ->and($translator->get('glucose_pages.index.head_title', [], 'en'))
        ->toBe('Glucose Exams')
        ->and($translator->get('vitamin_b12_pages.index.head_title', [], 'en'))
        ->toBe('Vitamin B12 Exams')
        ->and($translator->get('vitamin_d3_pages.index.head_title', [], 'en'))
        ->toBe('Vitamin D3 Exams')
        ->and($translator->get('complete_blood_count_pages.index.head_title', [], 'en'))
        ->toBe('Complete Blood Count')
        ->and($translator->get('flash.settings.profile.language.updated_successfully', [], 'pt_BR'))
        ->toBe('Idioma atualizado com sucesso.')
        ->and($translator->get('glucose_pages.index.head_title', [], 'pt_BR'))
        ->toBe('Exames de Glicose')
        ->and($translator->get('vitamin_b12_pages.index.head_title', [], 'pt_BR'))
        ->toBe('Exames de Vitamina B12')
        ->and($translator->get('vitamin_d3_pages.index.head_title', [], 'pt_BR'))
        ->toBe('Exames de Vitamina D3')
        ->and($translator->get('complete_blood_count_pages.index.head_title', [], 'pt_BR'))
        ->toBe('Hemograma Completo');
});

test('json translation strings still resolve alongside php language files', function () {
    $translator = app('translator');

    expect($translator->get('Log in', [], 'pt_BR'))->toBe('Entrar');
});
