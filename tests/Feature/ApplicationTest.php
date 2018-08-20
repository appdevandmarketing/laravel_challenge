<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_factory_was_updated()
    {
        factory(User::class)->create();
        $this->assertTrue(true);
    }

    public function test_registering_valid_user()
    {
        $this->postJson('/api/register', [
            'name' => 'tester',
            'email' => 'test@test.com',
            'password' => 'plaintextpassword',
            'password_confirmation' => 'plaintextpassword',
            'country' => 'usa',
            'date_of_birth' => '2018-01-01',
            'profession' => 'tester'
        ])
            ->assertStatus(201)
            ->assertDontSee('plaintextpassword')
            ->assertSee('id')
            ->assertSee('email')
            ->assertSee('registration_date')
            ->assertSee('date_of_birth')
            ->assertSee('country')
            ->assertSee('profession')
            ->assertSee('updated_at')
            ->assertSee('created_at');
    }

    public function test_registering_invalid_user()
    {
        $this->postJson('/api/register', [
            'name' => 'tester2',
            'email' => 'test2@test.com',
            'password' => 'plaintextpassword',
            'password_confirmation' => 'plaintextpassword',
            'country' => null,
            'date_of_birth' => null,
            'profession' => null
        ])
            ->assertStatus(422);
    }

    public function test_retrieving_user_info()
    {
        Passport::actingAs(
        // Need to update the factory or else this will fail
            factory(User::class)->create()
        );

        $response = $this->getJson('/api/user')
            ->assertDontSee('plaintextpassword')
            ->assertSee('id')
            ->assertSee('email')
            ->assertSee('registration_date')
            ->assertSee('date_of_birth')
            ->assertSee('country')
            ->assertSee('profession')
            ->assertSee('updated_at')
            ->assertSee('created_at');

        $response->assertStatus(201);
    }
    
    public function test_retrieving_user_info_without_auth_user()
    {
        $this->getJson('/api/user')->assertStatus(401);
    }


}
