<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SanityTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function app_boots_and_home_is_ok(): void
    {
        $this->get('/')->assertOk();
    }

    #[Test]
    public function auth_pages_are_reachable(): void
    {
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();
    }

    // İstersen aç:
    // use Illuminate\Support\Facades\Schema;
    // #[Test]
    // public function migrations_are_running(): void
    // {
    //     $this->assertTrue(Schema::hasTable('migrations'));
    //     $this->assertTrue(Schema::hasTable('customers'));
    // }
}
