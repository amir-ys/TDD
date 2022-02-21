<?php

namespace Tests\Feature\Middlewares;

use App\Http\Middleware\CheckUserIsAdmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class CheckUserIsAdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;
    public function test_when_user_is_not_admin()
    {
        $user =User::factory()->user()->create();
        $this->actingAs($user);

        $request = Request::create('/admin' ,'GET');
        $middleware = new CheckUserIsAdmin();

        $response = $middleware->handle($request , function (){});

        $this->assertEquals( $response->getStatusCode()  ,302);
    }

    public function test_when_user_is_admin()
    {
        $user =User::factory()->admin()->create();
        $this->actingAs($user);

        $request = Request::create('/admin' ,'GET');
        $middleware = new CheckUserIsAdmin();

        $response = $middleware->handle($request , function (){});

        $this->assertEquals( $response  ,null);
    }

    public function test_when_user_not_is_loggedin()
    {
        $request = Request::create('/admin' ,'GET');
        $middleware = new CheckUserIsAdmin();

        $response = $middleware->handle($request , function (){});

        $this->assertEquals( $response  ,null);
    }
}
