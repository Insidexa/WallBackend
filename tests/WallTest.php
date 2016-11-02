<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WallTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->getWalls();
    }

    public function getWalls () {
        $this->visit('/api/walls')
        ->post('/api/signin', ['email' => 'petro1@gmail.com', 'password' => 'foo'])
            ->seeJsonStructure(['walls']);
    }
}
