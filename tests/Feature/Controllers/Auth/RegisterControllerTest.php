<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
   use RefreshDatabase;

    public function test_user_can_register()
    {
        $this->withoutExceptionHandling();
        $data = User::factory()->state(['email_verified_at' => null])->make()->toArray();

        Event::fake();

        $this->post(route('register') , array_merge($data , [ 'password' => '12345678',
            'password_confirmation' => '12345678']));

        $this->assertDatabaseHas('users' , $data);
        Event::assertDispatched(Registered::class);


    }
}
