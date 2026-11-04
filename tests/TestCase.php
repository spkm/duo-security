<?php

namespace spkm\DuoSecurity\Tests;

use Illuminate\Support\Carbon;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Saloon\Http\Faking\MockClient;
use spkm\DuoSecurity\DuoSecurityServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [DuoSecurityServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('k', 32)));
        $app['config']->set('cache.default', 'array');
    }

    protected function tearDown(): void
    {
        MockClient::destroyGlobal();
        Carbon::setTestNow();

        parent::tearDown();
    }
}
