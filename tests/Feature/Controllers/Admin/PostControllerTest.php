<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_index_method()
    {
        $response =$this->get(route('admin.posts.index'));

        $response->assertOk();
        $response->assertViewIs('admins.posts.index');
        $response->assertViewHas('posts' , Post::latest()->paginate());
    }

    public function test_create_method()
    {
        $post =Post::factory()->create();
        $response =$this->get(route('admin.posts.create'));

        $response->assertOk();
        $response->assertViewIs('admins.posts.create');
        $response->assertViewHas([
            'tags' => Tag::latest()->paginate() ,
        ]);    }

    public function test_edit_method()
    {
        $post =Post::factory()->create();
        $response =$this->get(route('admin.posts.edit' , $post->id));

        $response->assertOk();
        $response->assertViewIs('admins.posts.edit');
        $response->assertViewHas([
            'post' => $post,
            'tags' => Tag::latest()->paginate() ,
            ]);
    }
}
