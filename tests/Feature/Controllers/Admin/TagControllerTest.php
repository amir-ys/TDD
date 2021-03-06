<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;
    protected array $middlewares = ['web', 'auth' ,'admin'  ];

    public function test_index_method()
    {
        $this->actingAsAdmin();
        $response = $this->get(route('admin.tags.index'));

        $response->assertOk()
            ->assertViewIs('admins.tags.index')
            ->assertViewHas('tags' , Tag::latest()->paginate());

        $this->checkRequestMiddlewares();
    }

    public function test_create_method()
    {
        $this->actingAsAdmin();
        $response = $this->get(route('admin.tags.create'));

        $response->assertOk()
            ->assertViewIs('admins.tags.create');

        $this->checkRequestMiddlewares();
    }

    public function test_edit_method()
    {
        $this->actingAsAdmin();
        $tag =Tag::factory()->create();
        $response = $this->get(route('admin.tags.edit' , $tag->id));

        $response->assertOk()
            ->assertViewIs('admins.tags.edit')
            ->assertViewHas('tag' , $tag);

        $this->checkRequestMiddlewares();
    }

    public function test_store_method()
    {
        $this->actingAsAdmin();
        $data =Tag::factory()->make()->toArray();

        $response = $this->post(route('admin.tags.store') , $data);

        $response->assertRedirect(route('admin.tags.index'))
            ->assertSessionHas('message' , 'tag created successfully');
        $this->assertDatabaseHas('tags' , $data);

        $this->checkRequestMiddlewares();
    }

    public function test_update_method()
    {
        $this->actingAsAdmin();
        $tag = Tag::factory()->create();
        $data =Tag::factory()->make()->toArray();

        $response = $this->patch(route('admin.tags.update' , $tag->id) , $data);

        $response->assertRedirect(route('admin.tags.index'))
            ->assertSessionHas('message' , 'tag updated successfully');
        $this->assertDatabaseHas('tags' , $data);
    }

    public function test_destroy_method()
    {
        $this->actingAsAdmin();
        $tag = Tag::factory()->create();

        $response = $this->delete(route('admin.tags.destroy' , $tag->id));

        $response->assertRedirect(route('admin.tags.index'))
            ->assertSessionHas('message' , 'tag deleted successfully');
        $this->assertModelMissing($tag);
        $this->assertDatabaseCount('tags' , 0);
    }

    public function test_validation_request_name_in_tag_data_must_be_required()
    {
        $data = [];
        $this->actingAsAdmin();

        $this->post(route('admin.tags.store') , $data)
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $this->actingAsAdmin();
        $tag = Tag::factory()->create();

        $this->patch(route('admin.tags.update' , $tag->id) , $data)
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);
    }

    private function actingAsAdmin()
    {
        return $this->actingAs(User::factory()->admin()->create());
    }

    private function checkRequestMiddlewares(): void
    {
        $this->assertEquals(request()->route()->middleware() ,$this->middlewares);
    }
}
