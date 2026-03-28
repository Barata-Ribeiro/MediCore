<?php

use Inertia\Inertia;
use Inertia\Testing\AssertableInertia as Assert;

test('returns a successful response', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('welcome')
            ->has('canRegister'),
        );
});

test('shared flash values are exposed through inertia', function () {
    Inertia::flash('success', 'It works');

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('welcome')
            ->hasFlash('success', 'It works'),
        );
});
