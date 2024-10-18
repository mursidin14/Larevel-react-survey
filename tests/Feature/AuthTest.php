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

    public function testGetUser()
    {
        $this->seed([UserSeeder::class]);
        $this->get('api/users', [
            'Authorization' => '_token'
        ])->assertStatus(200)
          ->assertJson([
            'data' => [
                'email' => 'test@gmail.com',
                'name' => 'testing'
            ]
          ]);
    }

    public function testGetUnauthorization()
    {
        $this->seed([UserSeeder::class]);
        $this->get('api/users', [
        ])->assertStatus(401)
          ->assertJson([
            'errors' => [
                'message' => [
                    'unauthorization'
                ]
            ]
          ]);
    }

    public function testWrongToken()
    {
        $this->seed([UserSeeder::class]);
        $this->get('api/users', [
            'Authorization' => 'salah'
        ])->assertStatus(401)
          ->assertJson([
            'errors' => [
                'message' => [
                    'unauthorization'
                ]
            ]
          ]);
    }

    public function testUpdatePasswordSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where('email', 'test@gmail.com')->first();

        $this->patch('api/users', [
            'password' => 'baru'
        ],
        [
            'Authorization' => '_token'
        ]
    )->assertStatus(200)
     ->assertJson([
        'data' => [
            'email' => 'test@gmail.com',
            'name' => 'testing'
        ]
     ]);

     $newUser = User::query()->where('email', 'test@gmail.com')->first();
     self::assertNotEquals($oldUser->password, $newUser->password);
    }

    public function testNameUpdateSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::query()->where('email', 'test@gmail.com')->first();

        $this->patch('api/users', [
            'name' => 'mure'
        ],
        [
            'Authorization' => '_token'
        ]
    )->assertStatus(200)
     ->assertJson([
        'data' => [
            'email' => 'test@gmail.com',
            'name' => 'mure'
        ]
     ]);

     $newUser = User::query()->where('email', 'test@gmail.com')->first();
     self::assertNotEquals($oldUser->name, $newUser->name);
    }


    public function testLogoutSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->delete( uri: '/api/users', headers: [
            'Authorization' => '_token'
        ])->assertStatus(200)
          ->assertJson([
                'data' => true
          ]);

          $user = User::query()->where('email', 'test@gmail.com')->first();
          self::assertNull($user->token);
    }

    public function testLogoutFiled()
    {
        $this->seed([UserSeeder::class]);

        $this->delete( uri: '/api/users', headers: [
            'Authorization' => 'salah'
        ])->assertStatus(401)
          ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorization'
                    ]
                ]
          ]);

    }
}
