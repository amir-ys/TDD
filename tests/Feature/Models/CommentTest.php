<?php

namespace Tests\Feature\Models;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_insert_data()
    {
        $data =  Comment::factory()->make()->toArray();

        Comment::create($data);

        $this->assertDatabaseCount( 'comments',1 );
    }

    public function test_comment_relation_with_post()
    {
        $comment =Comment::factory()->for( Post::factory() , 'commentable')->create();

        $this->assertTrue(isset($comment->commentable->id));
        $this->assertTrue($comment->commentable instanceof Post);
    }

    public function test_comment_relation_with_user()
    {
        $comment = Comment::factory()->for(User::factory())->create();

        $this->assertTrue(isset($comment->user->id));
        $this->assertTrue($comment->user instanceof User);
    }
}
