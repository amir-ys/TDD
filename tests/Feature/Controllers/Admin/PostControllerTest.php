<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;
    protected array $middlewares = ['web', 'admin'];
    public function test_index_method()
    {
        $this->actingAs(User::factory()->admin()->create());

        $response =$this->get(route('admin.posts.index'));

        $response->assertOk();
        $response->assertViewIs('admins.posts.index');
        $response->assertViewHas('posts' , Post::latest()->paginate());

        $this->assertEquals( ['web', 'admin'], request()->route()->middleware());
    }

    public function test_create_method()
    {
        $this->actingAs(User::factory()->admin()->create());

        $post =Post::factory()->create();
        $response =$this->get(route('admin.posts.create'));

        $response->assertOk();
        $response->assertViewIs('admins.posts.create');
        $response->assertViewHas([
            'tags' => Tag::latest()->paginate() ,
        ]);

        $this->assertEquals(['web' , 'admin'], request()->route()->middleware());
    }

    public function test_edit_method()
    {
        $this->actingAs(User::factory()->admin()->create());

        $post =Post::factory()->create();
        $response =$this->get(route('admin.posts.edit' , $post->id));

        $response->assertOk();
        $response->assertViewIs('admins.posts.edit');
        $response->assertViewHas([
            'post' => $post,  'tags' => Tag::latest()->paginate() ,
            ]);

        $this->assertEquals(['web' , 'admin'], request()->route()->middleware());
    }

    public function test_store_method()
    {
        $user = User::factory()->admin()->create();
        $data = Post::factory()->state(['user_id' => $user->id])->make()->toArray();
        $tags = Tag::factory()->count(rand(1,5))->create();

        $response = $this->actingAs($user)
            ->post(route('admin.posts.store') ,
                array_merge($data , [ 'tag_ids' => $tags->pluck('id')->toArray() ]));

        $response->assertRedirect(route('admin.posts.index'));
        $response->assertSessionHas('message' , 'post created successfully');
        $this->assertDatabaseHas('posts' , $data);
        $this->assertEquals(
            Post::query()->where($data)->first()->tags()->pluck('id')->toArray() ,
            $tags->pluck('id')->toArray()
        );

        $this->assertEquals(['web' , 'admin'], request()->route()->middleware());
    }

    public function test_update_method()
    {
        $user = User::factory()->admin()->create();
        $data = Post::factory()->state(['user_id' => $user->id])->make()->toArray();
        $tags = Tag::factory()->count(rand(1,5))->create();
        $post = Post::factory()->state(['user_id' => $user->id])->create();

        $response = $this->actingAs($user)
            ->patch(route('admin.posts.update' , $post->id) ,
                array_merge($data , [ 'tag_ids' => $tags->pluck('id')->toArray() ]));

        $response->assertRedirect(route('admin.posts.index'));
        $response->assertSessionHas('message' , 'post updated successfully');
        $this->assertDatabaseHas('posts' , array_merge($data , ['id' => $post->id]));
        $this->assertEquals(
            Post::query()->where($data)->first()->tags()->pluck('id')->toArray() ,
            $tags->pluck('id')->toArray()
        );

        $this->assertEquals(['web' , 'admin'], request()->route()->middleware());
    }
}
