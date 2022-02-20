<?php

namespace Tests\Feature\Middlewares;

use App\Http\Middleware\UserActivity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class UserActivityMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_activity_can_set_in_cache_when_user_is_logged_in()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request =Request::create('/' , 'GET');

        $middleware = new UserActivity();
       $response =  $middleware->handle($request , function (){});

       $this->assertNull($response);
        $this->assertEquals( Cache::get("user_{$user->id}_activity") ,'online');

       $this->travel(10 + 1)->seconds();

        $this->assertEquals(Cache::get("user_{$user->id}_activity") , null);
    }

    public function test_user_activity_can_not_set_in_cache_when_user_is_not_logged_in()
    {
        $user = User::factory()->create();
        $request =Request::create('/' , 'GET');

        $middleware = new UserActivity();
        $response =  $middleware->handle($request , function (){});

        $this->assertNull($response);
        $this->assertNull(Cache::get("user_{$user->id}_activity"));
    }


    public function test_user_activity_set_in_route_group()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get(route('home'));

        $this->assertEquals( Cache::get("user_{$user->id}_activity") ,'online');

    }
}
