<?php

namespace App\Http\Controllers\frontend;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function blogs()
    {
        $blogs = Blog::getRecords();
        return view('frontend.blogs', [
            'blogs' => $blogs
        ]);
    }

    public function blogView($slug)
    {
        $blog = Blog::findBySlug($slug);
        $blogs = Blog::recentBlogs();
        return view('frontend.blog-view', [
            'blog' => $blog,
            'blogs' => $blogs
        ]);
    }
}
