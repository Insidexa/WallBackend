<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {

        $this->signUp();
        $this->signIn();
        
    }

    public function signIn()
    {
        $this->post('/api/signin', ['email' => 'petro1@gmail.com', 'password' => 'foo'])
            ->seeJsonStructure(['token', 'user']);
    }

    public function signUp()
    {
        $user = factory(App\User::class)->create([
            'password' => bcrypt('foo'),
            'name' => 'user',
            'email' => 'petro1@gmail.com'
        ]);

        $this->post('/api/signin', ['email' => $user->email, 'password' => 'foo'])
            ->seeJsonStructure(['token', 'user']);
    }
}
