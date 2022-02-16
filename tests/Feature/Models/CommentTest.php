<?php

namespace Tests\Feature\Models;

use App\Models\Comment;
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
}
