<?php

namespace Models;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use tests\Feature\Models\ModelHelperTesting;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase ,ModelHelperTesting;

    public function model()
    {
        return new User();
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
