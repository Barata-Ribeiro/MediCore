<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->originalAppPath = app_path();
    $this->generatorPath = storage_path('framework/testing/generators/'.Str::uuid());

    app()->getNamespace();
    app()->useAppPath($this->generatorPath);
    File::ensureDirectoryExists(app_path('Providers'));
    File::copy($this->originalAppPath.'/Providers/AppServiceProvider.php', app_path('Providers/AppServiceProvider.php'));
});

afterEach(function () {
    app()->useAppPath($this->originalAppPath);
    File::deleteDirectory($this->generatorPath);
});

test('generates a resolvable service and interface without changing the provider', function (string $input, string $relativeName) {
    $provider = File::get(app_path('Providers/AppServiceProvider.php'));

    $this->artisan('make:service', ['name' => $input])->assertSuccessful();

    $servicePath = app_path('Services/'.$relativeName.'.php');
    $interfacePath = app_path('Interfaces/'.$relativeName.'Interface.php');
    $serviceClass = 'App\\Services\\'.str_replace('/', '\\', $relativeName);
    $interfaceClass = 'App\\Interfaces\\'.str_replace('/', '\\', $relativeName).'Interface';

    expect($interfacePath)->toBeFile();
    expect($servicePath)->toBeFile();
    expect(File::get($interfacePath))
        ->toContain('use Illuminate\\Container\\Attributes\\Bind;', '#[Bind(');

    require $interfacePath;
    require $servicePath;

    expect(app($interfaceClass))->toBeInstanceOf($serviceClass);
    expect(File::get(app_path('Providers/AppServiceProvider.php')))->toBe($provider);
})->with([
    'simple name' => ['GeneratedSimpleService', 'GeneratedSimpleService'],
    'nested slash' => ['Generated/SlashService', 'Generated/SlashService'],
    'nested backslash' => ['Generated\\BackslashService', 'Generated/BackslashService'],
    'qualified name' => ['App\\Services\\Generated\\QualifiedService', 'Generated/QualifiedService'],
    'leading backslash' => ['\\App\\Services\\Generated\\LeadingService', 'Generated/LeadingService'],
    'php extension' => ['Generated/ExtensionService.php', 'Generated/ExtensionService'],
    'attribute name collision' => ['Generated/Bind', 'Generated/Bind'],
]);

test('does not create an interface or change existing files when the service already exists', function () {
    $existingPath = 'Services/ExistingService.php';
    File::ensureDirectoryExists(dirname(app_path($existingPath)));
    File::put(app_path($existingPath), '<?php // Existing implementation');
    $provider = File::get(app_path('Providers/AppServiceProvider.php'));

    $this->artisan('make:service', ['name' => 'ExistingService'])->assertFailed();

    expect(File::get(app_path($existingPath)))->toBe('<?php // Existing implementation');
    expect(app_path('Interfaces/ExistingServiceInterface.php'))->not->toBeFile();
    expect(File::get(app_path('Providers/AppServiceProvider.php')))->toBe($provider);
});

test('does not create a service when its interface already exists', function () {
    File::ensureDirectoryExists(app_path('Interfaces'));
    File::put(app_path('Interfaces/ExistingServiceInterface.php'), '<?php // Existing contract');
    $provider = File::get(app_path('Providers/AppServiceProvider.php'));

    $this->artisan('make:service', ['name' => 'ExistingService'])->assertFailed();

    expect(app_path('Services/ExistingService.php'))->not->toBeFile();
    expect(File::get(app_path('Interfaces/ExistingServiceInterface.php')))->toBe('<?php // Existing contract');
    expect(File::get(app_path('Providers/AppServiceProvider.php')))->toBe($provider);
});

test('rejects reserved service names without creating an interface', function (string $name) {
    $provider = File::get(app_path('Providers/AppServiceProvider.php'));

    $this->artisan('make:service', ['name' => $name])->assertFailed();

    expect(File::allFiles($this->generatorPath))->toHaveCount(1);
    expect(File::get(app_path('Providers/AppServiceProvider.php')))->toBe($provider);
})->with(['class', 'Generated/class']);
