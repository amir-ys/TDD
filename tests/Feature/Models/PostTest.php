<?php

namespace Tests\Feature\Models;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase , ModelHelperTesting;

    public function model()
    {
        return new Post();
    }
    public function test_post_relation_with_user()
    {
        $post = Post::factory()->for(User::factory())->create();

        $this->assertTrue(isset($post->user->id));
        $this->assertInstanceOf(User::class ,$post->user);
    }

    public function test_post_relation_with_tag()
    {
        $count = rand(1, 10);
        $post = Post::factory()->hasTags($count)->create();

        $this->assertCount($count , $post->tags);
        $this->assertTrue($post->tags->first() instanceof Tag);
    }

    public function test_post_relation_with_comment()
    {
        $count = rand(1,9);
        $post = Post::factory()->hasComments($count)->create();

        $this->assertCount($count , $post->comments);
        $this->assertTrue($post->comments->first() instanceof Comment);
    }

}
