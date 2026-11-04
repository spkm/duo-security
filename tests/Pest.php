<?php

use Saloon\Config;
use spkm\DuoSecurity\Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| Every test boots the package in Testbench with an array cache. No request may reach a real Duo: anything sent
| without a mock client throws a StrayRequestException. Fake Duo with Tests\Helpers\FakeDuo.
|
*/

Config::preventStrayRequests();

pest()->extend(TestCase::class)->in('Feature', 'Spec');
