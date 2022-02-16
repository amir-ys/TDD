<?php

namespace Models;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_insert_data()
    {
        $data = User::factory()->make()->toArray();
        $data['password'] = 12345;
        User::create($data);

        $this->assertDatabaseCount('users' , 1);
        $this->assertDatabaseHas('users' , [ 'name' => $data['name']]);
    }

    public function test_user_relation_with_post()
    {
        $count = rand(1,10);
        $user = User::factory()->has(Post::factory()->count($count))->create();

        $this->assertCount($count, $user->posts);
        $this->assertTrue($user->posts->first() instanceof Post);
    }

    public function test_user_relation_with_comment()
    {
        $count = rand(1,9);
        $user = User::factory()->hasComments($count)->create();

        $this->assertCount($count ,$user->comments);
        $this->assertTrue($user->comments->first() instanceof Comment);
    }
}
