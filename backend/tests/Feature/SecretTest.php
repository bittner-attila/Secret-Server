<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use App\Models\Secret;
use Symfony\Component\HttpFoundation\Response;

class SecretTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_secret()
    {
        $response = $this->json('POST', '/api/secret', [
            'secret' => 'test secret',
            'expireAfterViews' => 10,
            'expireAfter' => 10,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_create_secret_when_expireAfterViews_is_zero()
    {
        $response = $this->json('POST', '/api/secret', [
            'secret' => 'test secret',
            'expireAfterViews' => 0,
            'expireAfter' => 10,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_show_secret()
    {
        $secret = Secret::create([
            'hash'       => Str::uuid(),
            'secretText' => 'test secret',
            'expiresAt'  => date('Y-m-d h:i:sa', strtotime('now + 20 min')),
            'remainingViews' => 10,
        ]);

        $response = $this->json('GET', 'api/secret/' . $secret->hash);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_show_secret_when_remainingViews_is_zero()
    {
        $secret = Secret::create([
            'hash'       => Str::uuid(),
            'secretText' => 'test secret',
            'expiresAt'  => date('Y-m-d h:i:sa', strtotime('now + 20 min')),
            'remainingViews' => 0,
        ]);

        $response = $this->get('api/secret/' . $secret->hash);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_show_secret_when_expiresAt_is_expired()
    {
        $secret = Secret::create([
            'hash'       => Str::uuid(),
            'secretText' => 'test secret',
            'expiresAt'  => '2010-10-10 10:10:10',
            'remainingViews' => 10,
        ]);

        $response = $this->get('api/secret/' . $secret->hash);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_create_and_show_secret()
    {
        $response = $this->json('POST', '/api/secret', [
            'secret' => 'test secret',
            'expireAfterViews' => 10,
            'expireAfter' => 10,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->get('api/secret/' . $response->getOriginalContent()->hash);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_when_run_out_of_remainingViews()
    {
        $secret = Secret::create([
            'hash'       => Str::uuid(),
            'secretText' => 'test secret',
            'expiresAt'  => date('Y-m-d h:i:sa', strtotime('now + 20 min')),
            'remainingViews' => 2,
        ]);

        $response = $this->get('api/secret/' . $secret->hash);

        $response->assertStatus(Response::HTTP_OK);

        $response = $this->get('api/secret/' . $secret->hash);

        $response->assertStatus(Response::HTTP_OK);

        $response = $this->get('api/secret/' . $secret->hash);

        $response->assertStatus(Response::HTTP_NOT_FOUND);

    }
}