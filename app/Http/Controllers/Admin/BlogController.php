<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index(Request $request)
    {

        $blogs = Blog::latest()->where(['is_active' => 1, 'is_deleted' => 0]);

        if (!empty($request->get('keyword'))) {
            $blogs = $blogs->where('title', 'like', '%' . $request->get('keyword') . '%');
        }

        $blogs = $blogs->paginate(10);

        return view('admin.blogs.index', compact('blogs'));
    }
    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => 'required|unique:blogs',
        ]);
        if ($validator->passes()) {

            $model = new Blog();
            $model->title = $request->title;
            $model->slug = $request->slug;
            $model->status = $request->status;
            $model->content = $request->content;
            $model->created_by = Auth::user()->id;
            $model->save();

            //save image
            if (!empty($request->image_id)) {
                $media = Media::find($request->image_id);
                $extArray = explode('.', $media->name);
                $ext = last($extArray);

                $newImageName = $model->id . time() . '.' . $ext;
                $sPath = public_path() . '/media/' . $media->name;
                $dPath = public_path() . '/uploads/blogs/' . $newImageName;
                File::copy($sPath, $dPath);

                //generate thumb
                // $dPath = public_path().'/uploads/company/thumb/'.$newImageName;
                // $img = Image::make($sPath);
                // // $img->resize(300, 200);
                // $img->fit(300, 200, function ($constraint) {
                //     $constraint->upsize();
                // });
                // $img->save($dPath);

                $model->image = $newImageName;
                $model->save();
            }

            $request->session()->flash('success', 'Blog added successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Blog added successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($id, Request $request)
    {

        $blog = Blog::find($id);
        if (empty($blog)) {
            return redirect()->route('admin.blog.index');
        }

        return view('admin.blogs.edit', compact('blog'));
    }
    public function update($id, Request $request)
    {
        $model = Blog::find($id);
        if (empty($model)) {
            $request->session()->flash('error', 'Blog not found.');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Blog not found.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => 'required|unique:blogs,slug,' . $model->id . ',id',
        ]);

        if ($validator->passes()) {

            $model->title = $request->title;
            $model->slug = $request->slug;
            $model->status = $request->status;
            $model->content = $request->content;
            $model->updated_by = Auth::user()->id;
            $model->save();

            $oldImage = $model->image;

            //save image
            if (!empty($request->image_id)) {
                $media = Media::find($request->image_id);
                $extArray = explode('.', $media->name);
                $ext = last($extArray);

                $newImageName = $model->id . time() . '.' . $ext;
                $sPath = public_path() . '/media/' . $media->name;
                $dPath = public_path() . '/uploads/blogs/' . $newImageName;
                File::copy($sPath, $dPath);

                //generate thumb
                // $dPath = public_path().'/uploads/company/thumb/'.$newImageName;
                // $img = Image::make($sPath);
                // // $img->resize(300, 200);
                // $img->fit(300, 200, function ($constraint) {
                //     $constraint->upsize();
                // });
                // $img->save($dPath);

                $model->image = $newImageName;
                $model->save();

                //delete old image
                // File::delete(public_path().'/uploads/company/thumb/'.$oldImage);
                File::delete(public_path() . '/uploads/blogs/' . $oldImage);
            }

            $request->session()->flash('success', 'Blog updated successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Blog updated successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy($id, Request $request)
    {
        $model = Blog::find($id);
        if (empty($model)) {
            $request->session()->flash('error', 'Blog not found.');
            return response()->json([
                'status' => true,
                'message' => 'Blog not found.'
            ]);
        }

        $model->is_active = 0;
        $model->is_deleted = 1;
        $model->save();

        // File::delete(public_path().'/uploads/page/thumb/'.$model->image);
        // File::delete(public_path().'/uploads/page/'.$model->image);

        $request->session()->flash('success', 'Blog deleted successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Blog deleted successfully.'
        ]);
    }

    public function getSlug($id, Request $request)
    {

        $model = Blog::find($id);
        if (empty($model)) {
            $request->session()->flash('error', 'Blog not found.');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Blog not found.'
            ]);
        }
    }
}
