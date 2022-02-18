<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_index_method()
    {
        $this->actingAs(User::factory()->admin()->create());

        $response =$this->get(route('admin.posts.index'));

        $response->assertOk();
        $response->assertViewIs('admins.posts.index');
        $response->assertViewHas('posts' , Post::latest()->paginate());

        $this->assertEquals( ['web', 'admin'], request()->route()->middleware());
    }

    public function test_create_method()
    {
        $this->actingAs(User::factory()->admin()->create());

        $post =Post::factory()->create();
        $response =$this->get(route('admin.posts.create'));

        $response->assertOk();
        $response->assertViewIs('admins.posts.create');
        $response->assertViewHas([
            'tags' => Tag::latest()->paginate() ,
        ]);

        $this->assertEquals(['web' , 'admin'], request()->route()->middleware());
    }

    public function test_edit_method()
    {
        $this->actingAs(User::factory()->admin()->create());

        $post =Post::factory()->create();
        $response =$this->get(route('admin.posts.edit' , $post->id));

        $response->assertOk();
        $response->assertViewIs('admins.posts.edit');
        $response->assertViewHas([
            'post' => $post,  'tags' => Tag::latest()->paginate() ,
            ]);

        $this->assertEquals(['web' , 'admin'], request()->route()->middleware());
    }
}
