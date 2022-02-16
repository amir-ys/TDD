<?php

namespace Tests\Feature\Models;

use App\Models\Post;
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
}
