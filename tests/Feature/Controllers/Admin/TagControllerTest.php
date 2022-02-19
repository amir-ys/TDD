<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_index_method()
    {
        $this->actingAsAdmin();
        $response = $this->get(route('admin.tags.index'));

        $response->assertOk()
            ->assertViewIs('admins.tags.index')
            ->assertViewHas('tags' , Tag::latest()->paginate());
    }

    public function test_create_method()
    {
        $this->actingAsAdmin();
        $response = $this->get(route('admin.tags.create'));

        $response->assertOk()
            ->assertViewIs('admins.tags.create');
    }

    public function test_edit_method()
    {
        $this->actingAsAdmin();
        $tag =Tag::factory()->create();
        $response = $this->get(route('admin.tags.edit' , $tag->id));

        $response->assertOk()
            ->assertViewIs('admins.tags.edit')
            ->assertViewHas('tag' , $tag);
    }





    private function actingAsAdmin()
    {
        return $this->actingAs(User::factory()->admin()->create());
    }
}
