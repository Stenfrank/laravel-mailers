<?php

namespace Stenfrank\LaravelMailers\Tests;

use Stenfrank\LaravelMailers\LaravelMailersServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelMailersServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // 
    }
}
