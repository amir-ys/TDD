<?php

namespace Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_insert_data()
    {
        $data = User::factory()->make()->toArray();
        $data['password'] = 12345;
        User::create($data);

        $this->assertDatabaseCount('users' , 1);
        $this->assertDatabaseHas('users' , [ 'name' => $data['name']]);
    }
}
