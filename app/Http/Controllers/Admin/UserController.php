<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function show(User $user): Factory|View|Application
    {
        $status = Cache::get("user_". auth()->id() ."_activity") ;
        return view('admins.users.show' , compact('user' , 'status'));
    }
}
