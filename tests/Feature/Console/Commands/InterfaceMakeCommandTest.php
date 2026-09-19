<?php

use App\Services\DashboardService;
use App\Services\Exams\GlucoseService;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->originalAppPath = app_path();
    $this->generatorPath = storage_path('framework/testing/generators/'.Str::uuid());

    app()->getNamespace();
    app()->useAppPath($this->generatorPath);
    File::ensureDirectoryExists(app_path('Providers'));
    File::copy("{$this->originalAppPath}/Providers/AppServiceProvider.php", app_path('Providers/AppServiceProvider.php'));
});

afterEach(function () {
    app()->useAppPath($this->originalAppPath);
    File::deleteDirectory($this->generatorPath);
});

test('generates standalone interfaces without a service binding', function () {
    $this->artisan('make:interface', ['name' => 'Generated/StandaloneContract'])->assertSuccessful();

    $path = app_path('Interfaces/Generated/StandaloneContract.php');

    require $path;

    expect(interface_exists('App\\Interfaces\\Generated\\StandaloneContract'))->toBeTrue();
    expect(File::get($path))->not->toContain('Bind', '{{');
});

test('binds an interface to the requested service', function (string $name, string $service, string $expectedService) {
    $provider = File::get(app_path('Providers/AppServiceProvider.php'));

    $this->artisan('make:interface', ['name' => $name, '--service' => $service])->assertSuccessful();

    $path = app_path('Interfaces/'.$name.'.php');

    require $path;

    $interface = new ReflectionClass('App\\Interfaces\\'.str_replace('/', '\\', $name));
    $binding = $interface->getAttributes(Bind::class)[0]->newInstance();

    expect($binding->concrete)->toBe($expectedService);
    expect(File::get(app_path('Providers/AppServiceProvider.php')))->toBe($provider);
})->with([
    'relative service' => ['Generated/RelativeContract', 'Exams/GlucoseService', GlucoseService::class],
    'qualified service' => ['Generated/QualifiedContract', DashboardService::class, DashboardService::class],
    'same class name' => ['Generated/SharedName', 'Generated/SharedName', 'App\\Services\\Generated\\SharedName'],
]);

test('preserves existing interfaces and fails instead of overwriting them', function () {
    File::ensureDirectoryExists(app_path('Interfaces'));
    File::put(app_path('Interfaces/ExistingContract.php'), '<?php // Existing contract');

    $this->artisan('make:interface', ['name' => 'ExistingContract', '--service' => 'DashboardService'])
        ->assertFailed();

    expect(File::get(app_path('Interfaces/ExistingContract.php')))->toBe('<?php // Existing contract');
});
