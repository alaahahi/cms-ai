<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class BaselineTestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed necessary data for parallel auth (legacy types + Spatie roles)
        $this->artisan('db:seed', ['--class' => 'UserTypeSeeder']);
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }
}
