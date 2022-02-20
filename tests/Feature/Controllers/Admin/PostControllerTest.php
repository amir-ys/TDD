<?php

namespace Tests\Feature\Controllers\Admin;

use App\Helpers\DurationOfReading;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\ExpectationInterface;
use Mockery\MockInterface;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;
    protected array $middlewares = ['web', 'auth' ,'admin'  ];
    public function test_index_method()
    {
        $this->actingAsAdmin();
        $response =$this->get(route('admin.posts.index'));

        $response->assertOk();
        $response->assertViewIs('admins.posts.index');
        $response->assertViewHas('posts' , Post::latest()->paginate());

        $this->checkRequestMiddlewares();
    }

    public function test_create_method()
    {
        $this->actingAsAdmin();

        $post =Post::factory()->create();
        $response =$this->get(route('admin.posts.create'));

        $response->assertOk();
        $response->assertViewIs('admins.posts.create');
        $response->assertViewHas([
            'tags' => Tag::latest()->paginate() ,
        ]);

        $this->checkRequestMiddlewares();
    }

    public function test_edit_method()
    {
        $this->actingAsAdmin();

        $post =Post::factory()->create();
        $response =$this->get(route('admin.posts.edit' , $post->id));

        $response->assertOk();
        $response->assertViewIs('admins.posts.edit');
        $response->assertViewHas([
            'post' => $post,  'tags' => Tag::latest()->paginate() ,
            ]);

        $this->checkRequestMiddlewares();
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

        $this->checkRequestMiddlewares();
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
            $tags->pluck('id')->toArray() ,
        );
    }

    public function test_validation_request_post_data_has_required()
    {
        $this->actingAsAdmin();
        $data = [];
        $errors = [
            'title' =>  'The title field is required.',
            'description' =>  'The description field is required.',
            'image' =>  'The image field is required.',
            'tag_ids' =>  'The tag ids field is required.',
        ];


        $this->post(route('admin.posts.store') , $data)
        ->assertSessionHasErrors($errors);

        $this->post(route('admin.posts.update' , Post::factory()->create()->id) , $data)
            ->assertSessionHasErrors($errors);
    }

    public function test_validation_request_description_field_in_post_data_has_min()
    {
        $this->actingAsAdmin();
        $data = [ 'description' => 'lord' ];
        $errors = [
            'description' =>  'The description must be at least 5 characters.',
        ];

        $this->post(route('admin.posts.store') , $data)
            ->assertSessionHasErrors($errors);

        $this->post(route('admin.posts.update' , Post::factory()->create()->id) , $data)
            ->assertSessionHasErrors($errors);
    }

    public function test_validation_request_image_field_in_post_data_has_url()
    {
        $this->actingAsAdmin();
        $data = [ 'image' => 'lord' ];
        $errors = [
            'image' =>  'The image must be a valid URL.',
        ];

        $this->post(route('admin.posts.store') , $data)
            ->assertSessionHasErrors($errors);

        $this->post(route('admin.posts.update' , Post::factory()->create()->id) , $data)
            ->assertSessionHasErrors($errors);
    }

    public function test_validation_request_tags_field_in_post_data_exits_in_tag_table()
    {
        $this->actingAsAdmin();
        $data = [ 'tag_ids' => 'lord' ];
        $errors = [
            'tag_ids' =>  'The tag ids must be an array.',
        ];

        $this->post(route('admin.posts.store') , $data)
            ->assertSessionHasErrors($errors);

        $this->post(route('admin.posts.update' , Post::factory()->create()->id) , $data)
            ->assertSessionHasErrors($errors);

        $this->actingAsAdmin();
        $data = [ 'tag_ids' => [0] ];
        $errors = [
            'tag_ids.0' => 'The selected tag_ids.0 is invalid.',
        ];

        $this->post(route('admin.posts.store') , $data)
            ->assertSessionHasErrors($errors);

        $this->post(route('admin.posts.update' , Post::factory()->create()->id) , $data)
            ->assertSessionHasErrors($errors);

    }

    public function test_destroy_method()
    {
        $this->actingAsAdmin();
        $post = Post::factory()->hasTags(5)->hasComments(20)->create();

        $response = $this->delete(route('admin.posts.destroy' , $post->id));

        $response->assertRedirect(route('admin.posts.index'))
        ->assertSessionHas('message' , 'post deleted successfully');
        $this->assertDatabaseMissing('posts' , $post->toArray());
        $this->assertCount(0, $post->comments);
        $this->assertEmpty($post->tags);

        $this->checkRequestMiddlewares();
    }

    public function test_get_duration_of_reading_attribute()
    {
        $post = Post::factory()->make();
        $dor = (new DurationOfReading())->setText($post->description)->getDurationPerMinutes();

        $this->assertEquals( $post->readingDuration , $dor);
    }

    private function actingAsAdmin(){
       return $this->actingAs(User::factory()->admin()->create());
    }

    private function checkRequestMiddlewares(): void
    {
        $this->assertEquals(request()->route()->middleware() ,$this->middlewares);
    }
}
