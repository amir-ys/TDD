<?php

namespace Tests\Feature\Views;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SingleViewTest extends TestCase
{
    public function test_single_view_rendered_when_user_is_loggedin()
    {
        $post = Post::factory()->create();
        $comments = [];

        $view =  (string) $this->actingAs(User::factory()->create())->view('single' ,
            compact('post' , 'comments'));

        $dom = new \DOMDocument();
        $dom->loadHTML($view);
        $dom = new \DOMXPath($dom);
        $route = route('single.comment' , $post);

        $this->assertCount(1 ,
            $dom->query("//form[@method='post'][@action='$route']/textarea[@name='text']"));
    }

    public function test_single_view_not_rendered_when_user_is_not_loggedin()
    {
        $post = Post::factory()->create();
        $comments = [];

        $view =  (string) $this->view('single' ,
            compact('post' , 'comments'));

        $dom = new \DOMDocument();
        $dom->loadHTML($view);
        $dom = new \DOMXPath($dom);
        $route = route('single.comment' , $post);

        $this->assertCount(0 ,
            $dom->query("//form[@method='post'][@action='$route']/textarea[@name='text']"));
    }
}
