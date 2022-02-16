<?php

namespace Tests\Feature\Models;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function test_insert_data()
    {
        $data =  Tag::factory()->make()->toArray();

        Tag::create($data);

        $this->assertDatabaseCount( 'tags',1 );
    }

    public function test_tag_relation_with_post()
    {
        $count = rand(1 ,10);
       $tag = Tag::factory()->hasPosts($count)->create();

       $this->assertCount($count , $tag->posts);
       $this->assertTrue($tag->posts->first() instanceof Post);
    }
}
