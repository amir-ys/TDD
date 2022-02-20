<?php

namespace Tests\Feature\Controllers\Admin;

use App\Http\Middleware\UserActivity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_method()
    {
        $this->withoutMiddleware(UserActivity::class);

        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        Cache::shouldReceive('get')
            ->with("user_{$user->id}_activity")
            ->once()
            ->andReturn('online');

        $this->get(route('admin.users.show' , $user))
            ->assertViewIs('admins.users.show')
            ->assertViewHas([
                'user' => $user ,
                'status' => 'online'
            ]);

    }
}
