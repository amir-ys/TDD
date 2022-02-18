<?php

namespace tests\Feature\Models;

use App\Models\Comment;
use App\Models\User;

trait ModelHelperTesting
{
    public function test_insert_data()
    {
        $data =  $this->model()::factory()->make()->toArray();
        if ($this->model() instanceof User){
            $data['password'] = 123456;
        }

        $this->model()::create($data);

        $this->assertDatabaseCount( $this->model()->getTable() ,1 );
        $this->assertDatabaseHas( $this->model()->getTable() , $data );
    }

    abstract public function model();

}
