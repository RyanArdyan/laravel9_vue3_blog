<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Models\Post;

class DashboardPostsController extends Controller
{
    public function index()
    {
        return PostResource::collection(
            // ambil postingan yang column user_id nya berisi id user yg login
            Post::where('user_id', auth()->user()->id)->latest()->get()
        );
    }
}
