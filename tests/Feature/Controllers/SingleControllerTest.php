<?php

namespace Tests\Feature\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SingleControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * test single page with post and comments
     *
     * @return void
     */
    public function test_index_method()
    {
        $post = Post::factory()->has(Comment::factory()->count(rand(1,3)))->create();
        $response = $this->get(route('single' , $post->id));

        $response->assertOk();
        $response->assertViewIs('single');
//        $response->assertViewHas('post' ,  $post );
//        $response->assertViewHas('comments' ,  $post->comments()->latest()->paginate() );
    }
}
