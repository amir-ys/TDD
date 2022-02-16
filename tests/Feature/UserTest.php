<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_inset_data_to_user_table()
    {
        $data = User::factory()->make()->toArray();
        $data['password'] = 12345;
        User::create($data);
        $this->assertDatabaseCount('users' , 1);
        $this->assertDatabaseHas('users' , [ 'name' => $data['name']]);
    }
}
