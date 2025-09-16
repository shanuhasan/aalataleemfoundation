<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $teamMembers = \App\Models\Team::where(['is_active' => 1, 'is_deleted' => 0,'status' => 1])->limit(3)->get();
        $blogs = \App\Models\Blog::where(['is_active' => 1, 'is_deleted' => 0,'status' => 1])->limit(3)->get();
        return view('frontend.index', compact('teamMembers', 'blogs'));
    }
}
