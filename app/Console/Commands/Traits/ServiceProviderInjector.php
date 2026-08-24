<?php

namespace App\Console\Commands\Traits;

use ReflectionClass;
use RuntimeException;

use function sprintf;

trait ServiceProviderInjector
{
    public function injectCodeToRegisterMethod(string $appServiceProviderFile, string $codeToAdd): void
    {
        $reflectionClass = new ReflectionClass('App\\Providers\\AppServiceProvider');
        $reflectionMethod = $reflectionClass->getMethod('register');

        $methodBody = file($appServiceProviderFile);
        if ($methodBody === false) {
            throw new RuntimeException(sprintf('Unable to read service provider file: %s', $appServiceProviderFile));
        }

        $endLine = $reflectionMethod->getEndLine() - 1;

        array_splice($methodBody, $endLine, 0, $codeToAdd);
        $modifiedCode = implode('', $methodBody);

        file_put_contents($appServiceProviderFile, $modifiedCode);
    }
}
