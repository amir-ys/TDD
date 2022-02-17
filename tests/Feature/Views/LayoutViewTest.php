<?php

namespace Tests\Feature\Views;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LayoutViewTest extends TestCase
{
    /**
     * test user with permission admin can see adminPanel link
     *
     * @return void
     */
    public function test_view_rendered_when_user_is_admin()
    {
       $this->actingAs( User::factory()->state(['type' => 'admin'])->create());
        $view = $this->view('home');

        $view->assertSee('<a href="/admin/dashboard"> login to admin panel </a>' , false);
    }

    /**
     * test user without permission admin can not see adminPanel link
     *
     * @return void
     */
    public function test_view_rendered_when_user_is_not_admin()
    {
        $this->actingAs( User::factory()->state(['type' => 'user'])->create());
        $view = $this->view('home');

        $view->assertDontSee('<a href="/admin/dashboard"> login to admin panel </a>' , false);
    }
}
