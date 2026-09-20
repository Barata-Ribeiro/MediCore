<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class InterfaceMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:interface {name} {--service= : The service implementation to bind}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Interface class';

    public function handle(): ?bool
    {
        $result = parent::handle();

        if ($result === false) {
            $this->fail('Interface could not be created.');
        }

        return $result;
    }

    protected function buildClass($name): string
    {
        $class = parent::buildClass($name);
        $service = $this->option('service');

        if (! $service) {
            return str_replace('{{ serviceBinding }}', '', $class);
        }

        $service = str_replace('/', '\\', ltrim($service, '\\/'));

        if (! Str::startsWith($service, $this->rootNamespace())) {
            $service = $this->rootNamespace().'Services\\'.$service;
        }

        $serviceClass = class_basename($service);
        $serviceImport = $service;

        if (in_array(strtolower($serviceClass), [strtolower(class_basename($name)), 'bind'], true)) {
            $serviceClass = 'ServiceImplementation';
            $serviceImport .= ' as '.$serviceClass;
        }

        return str_replace('{{ serviceBinding }}', "use {$serviceImport};\nuse Illuminate\\Container\\Attributes\\Bind;\n\n#[Bind({$serviceClass}::class)]\n", $class);
    }

    /**
     * Execute the console command.
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\\Interfaces';
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return __DIR__.'/stubs/interface.stub';
    }
}
