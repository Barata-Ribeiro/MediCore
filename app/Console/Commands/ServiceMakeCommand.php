<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;

class ServiceMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Service class';

    /**
     * Execute the console command.
     *
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
        $name = $this->qualifyClass($this->getNameInput());

        if ($this->isReservedName(class_basename($name))) {
            $this->fail('The name "'.class_basename($name).'" is reserved by PHP.');
        }

        if ($this->alreadyExists($name)) {
            $this->fail('Service already exists.');
        }

        $interface = $this->qualifyInterfaceFqn($name);

        if ($this->files->exists($this->getPath($interface))) {
            $this->fail('Interface already exists. Add its binding and implementation manually.');
        }

        if ($this->call('make:interface', ['name' => $interface, '--service' => $name]) !== self::SUCCESS) {
            $this->fail('The service interface could not be created.');
        }

        return parent::handle();
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return __DIR__.'/stubs/service.stub';
    }

    /**
     * Execute the console command.
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return "{$rootNamespace}\\Services";
    }

    protected function buildClass($name): string
    {
        $class = parent::buildClass($name);
        $interfaceFqn = $this->qualifyInterfaceFqn($name);

        return str_replace('{{ interfaceFqn }}', $interfaceFqn, $class);
    }

    protected function qualifyInterfaceFqn(string $name): string
    {
        $rootNamespace = rtrim($this->rootNamespace(), '\\');
        $relativeName = Str::after($name, $rootNamespace.'\\Services\\');

        return "{$rootNamespace}\\Interfaces\\{$relativeName}Interface";
    }
}
