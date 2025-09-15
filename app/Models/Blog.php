<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    public static function getRecords()
    {
        return self::latest()->where(['is_active' => 1, 'is_deleted' => 0, 'status' => 1])->paginate(10);
    }

    public static function recentBlogs()
    {
        return self::latest()->where(['is_active' => 1, 'is_deleted' => 0, 'status' => 1])->limit(3)->get();
    }

    public static function findBySlug($slug)
    {
        return self::latest()->where(['slug' => $slug, 'is_active' => 1, 'is_deleted' => 0, 'status' => 1])->first();
    }

    static public function findByGuid($guid)
    {
        return self::where('guid', $guid)->first();
    }
}
