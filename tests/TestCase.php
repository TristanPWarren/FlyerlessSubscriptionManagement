<?php

namespace Flyerless\Tests\FlyerlessSubscriptionManagement;

use Flyerless\FlyerlessSubscriptionManagement\ModuleServiceProvider;
use BristolSU\Support\Testing\AssertsEloquentModels;
use BristolSU\Support\Testing\CreatesModuleEnvironment;
use BristolSU\Support\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use AssertsEloquentModels, CreatesModuleEnvironment;

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return array_merge(parent::getPackageProviders($app), [
            ModuleServiceProvider::class
        ]);
    }
    
}