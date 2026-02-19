<?php

namespace Tests\Feature;

use App\Models\License;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LicenseValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_license_returns_success(): void
    {
        $license = License::create([
            'key' => 'TEST-1234-5678-ABCD',
            'is_active' => true,
            'expires_at' => now()->addYear(),
        ]);

        $response = $this->postJson('/api/validate-license', [
            'license_key' => $license->key,
            'domain' => 'example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success', 'message' => 'License validation successful']);
    }

    public function test_valid_license_binds_domain_on_first_use(): void
    {
        $license = License::create([
            'key' => 'BIND-TEST-1234-5678',
            'domain' => null,
            'is_active' => true,
            'expires_at' => now()->addYear(),
        ]);

        $this->postJson('/api/validate-license', [
            'license_key' => $license->key,
            'domain' => 'mysite.com',
        ])->assertStatus(200);

        $license->refresh();
        $this->assertSame('mysite.com', $license->domain);
    }

    public function test_invalid_license_key_returns_403(): void
    {
        $response = $this->postJson('/api/validate-license', [
            'license_key' => 'INVALID-KEY-1234-5678',
            'domain' => 'example.com',
        ]);

        $response->assertStatus(403)
            ->assertJson(['status' => 'error', 'message' => 'License validation failed']);
    }

    public function test_expired_license_returns_403(): void
    {
        $license = License::create([
            'key' => 'EXPIRED-1234-5678-ABCD',
            'is_active' => true,
            'expires_at' => now()->subDay(),
        ]);

        $response = $this->postJson('/api/validate-license', [
            'license_key' => $license->key,
            'domain' => 'example.com',
        ]);

        $response->assertStatus(403)
            ->assertJson(['status' => 'error', 'message' => 'License validation failed']);
    }

    public function test_inactive_license_returns_403(): void
    {
        $license = License::create([
            'key' => 'INACTIVE-1234-5678-ABCD',
            'is_active' => false,
            'expires_at' => now()->addYear(),
        ]);

        $response = $this->postJson('/api/validate-license', [
            'license_key' => $license->key,
            'domain' => 'example.com',
        ]);

        $response->assertStatus(403)
            ->assertJson(['status' => 'error', 'message' => 'License validation failed']);
    }

    public function test_domain_mismatch_returns_403(): void
    {
        $license = License::create([
            'key' => 'BOUND-1234-5678-ABCD',
            'domain' => 'original.com',
            'is_active' => true,
            'expires_at' => now()->addYear(),
        ]);

        $response = $this->postJson('/api/validate-license', [
            'license_key' => $license->key,
            'domain' => 'other.com',
        ]);

        $response->assertStatus(403)
            ->assertJson(['status' => 'error', 'message' => 'License validation failed']);
    }

    public function test_domain_normalization_accepts_www(): void
    {
        $license = License::create([
            'key' => 'WWW-TEST-1234-5678',
            'domain' => 'example.com',
            'is_active' => true,
            'expires_at' => now()->addYear(),
        ]);

        $response = $this->postJson('/api/validate-license', [
            'license_key' => $license->key,
            'domain' => 'www.example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);
    }

    public function test_missing_domain_returns_validation_error(): void
    {
        $response = $this->postJson('/api/validate-license', [
            'license_key' => 'SOME-KEY',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['domain']);
    }

    public function test_missing_license_key_returns_validation_error(): void
    {
        $response = $this->postJson('/api/validate-license', [
            'domain' => 'example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['license_key']);
    }
}
