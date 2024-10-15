<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{

    // test register success
    public function testRegisterSuccess()
    {
        $this->post('api/users', [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'password'
        ])->assertStatus(201)
          ->assertJson([
            'data' => [
                'name' => 'test',
                'email' => 'test@gmail.com'
            ]
          ]);
    }

    // test register failed
    public function testRegisterFailed()
    {
        $this->post('api/users', [
            'name' => 'test',
            'email' => '',
            'password' => 'password',
        ])->assertStatus(400)
          ->assertJson([
            'errors' => [
                'email' => [
                    'The email field is required.'
                ]
            ]
          ]);
    }

    // test register already
    public function testRegisterAlready()
    {
        $this->testRegisterSuccess();
        $this->post('api/users', [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'password',
        ])->assertStatus(422)
          ->assertJson([
            'errors' => [
                'email' => [
                    'email is already'
                ]
            ]
          ]);
    }

    // login test success
    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->post('api/users/login', [
            'email' => 'test@gmail.com',
            'password' => 'password',
        ])->assertStatus(200)
          ->assertJson([
            'data' => [
                'name' => 'testing',
                'email' => 'test@gmail.com',
            ]
            ]);

        $user = User::where('email', 'test@gmail.com')->first();
        self::assertNotNull($user->token);
    }

    // test login failed
    public function testLoginFailedEmail()
    {
        $this->seed([UserSeeder::class]);
        $this->post('api/users/login', [
            'email' => 'salah@gmail.com',
            'password' => 'password',
        ])->assertStatus(401)
          ->assertJson([
            'errors' => [
                'message' => [
                    'email or password is wrong'
                ]
            ]
                ]);
    }

    // test login failed password
    public function testLoginFailedPassword()
    {
        $this->seed(UserSeeder::class);
        $this->post('api/users/login', [
            'email' => 'test@gmail.com',
            'password' => 'salah',
        ])->assertStatus(401)
          ->assertJson([
            'errors' => [
                'message' => [
                    'email or password is wrong'
                ]
            ]
                ]);
    }
}
