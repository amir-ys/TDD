<?php

namespace Tests\Feature\Models;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_insert_data()
    {
       $data =  Post::factory()->make()->toArray();

       Post::create($data);

       $this->assertDatabaseCount( 'posts',1 );
    }

    public function test_post_relation_with_user()
    {
        $post = Post::factory()->for(User::factory())->create();

        $this->assertTrue(isset($post->user->id));
        $this->assertTrue($post->user instanceof User);
    }

    public function test_post_relation_with_tag()
    {
        $count = rand(1, 10);
        $post = Post::factory()->hasTags($count)->create();

        $this->assertCount($count , $post->tags);
        $this->assertTrue($post->tags->first() instanceof Tag);
    }


}
