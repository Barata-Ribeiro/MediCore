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
        ->and($translator->get('flash.settings.profile.language.updated_successfully', [], 'pt_BR'))
        ->toBe('Idioma atualizado com sucesso.');
});

test('json translation strings still resolve alongside php language files', function () {
    $translator = app('translator');

    expect($translator->get('Log in', [], 'pt_BR'))->toBe('Entrar');
});
