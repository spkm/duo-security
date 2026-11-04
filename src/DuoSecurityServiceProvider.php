<?php

namespace spkm\DuoSecurity;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use spkm\DuoSecurity\Data\Data;
use spkm\DuoSecurity\Requests\DuoRequest;
use Symfony\Component\VarDumper\Caster\Caster;
use Symfony\Component\VarDumper\Cloner\AbstractCloner;

class DuoSecurityServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('duo-security')
            ->hasConfigFile('duo-security')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->publishConfigFile();
            });

        $this->app->singleton('duo-security', fn ($app) => new DuoSecurityService);

        $this->redactInDumps();
    }

    /**
     * Teaches VarDumper (dump(), dd(), Telescope's dump watcher) to show a model's secrets and a request's secret
     * parameters redacted: it reads an object's properties whatever __debugInfo() says.
     */
    protected function redactInDumps(): void
    {
        if (! class_exists(AbstractCloner::class)) {
            return;
        }

        AbstractCloner::$defaultCasters[Data::class] = function (Data $model, array $properties): array {
            foreach ($model::hidden() as $name) {
                if (($properties[$name] ?? null) !== null) {
                    $properties[$name] = '[hidden]';
                }
            }

            return $properties;
        };

        AbstractCloner::$defaultCasters[DuoRequest::class] = function (DuoRequest $request, array $properties): array {
            foreach (['params', 'payload'] as $name) {
                $key = Caster::PREFIX_PROTECTED.$name;

                if (is_array($properties[$key] ?? null)) {
                    $properties[$key] = DuoRequest::redact($properties[$key]);
                }
            }

            return $properties;
        };
    }
}
