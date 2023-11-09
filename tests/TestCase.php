<?php


namespace RaadaaPartners\RaadaaBase\Tests;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\SanctumServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use RaadaaPartners\RaadaaBase\RaadaaBaseServiceProvider;

class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            RaadaaBaseServiceProvider::class,
            SanctumServiceProvider::class,
        ];
    }
}