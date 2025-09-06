<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index(Request $request){

        $pages = Page::latest()->where(['is_active'=>1,'is_deleted'=>0]);

        if(!empty($request->get('keyword')))
        {
            $pages = $pages->where('name','like','%'.$request->get('keyword').'%');
        }

        $pages = $pages->paginate(10);

        return view('admin.page.index',compact('pages'));
    }
    public function create(){
        return view('admin.page.create');
    }

    public function store(Request $request){
        
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:pages',
        ]);
        if($validator->passes()){

            $model = new Page();
            $model->name = $request->name;
            $model->slug = $request->slug;
            $model->content = $request->content;
            $model->save();

            //save image
            // if(!empty($request->image_id)){
            //     $tempImage = TempImage::find($request->image_id);
            //     $extArray = explode('.',$tempImage->name);
            //     $ext = last($extArray);

            //     $newImageName = $model->id .time(). '.'.$ext;
            //     $sPath = public_path().'/temp/'.$tempImage->name;
            //     $dPath = public_path().'/uploads/page/'.$newImageName;
            //     File::copy($sPath,$dPath);

            //     //generate thumb
            //     $dPath = public_path().'/uploads/page/thumb/'.$newImageName;
            //     $img = Image::make($sPath);
            //     // $img->resize(300, 200);
            //     $img->fit(300, 200, function ($constraint) {
            //         $constraint->upsize();
            //     });
            //     $img->save($dPath);

            //     $model->image = $newImageName;
            //     $model->save();
                
            // }

            $request->session()->flash('success','Page added successfully.');
            return response()->json([
                'status'=>true,
                'message'=>'Page added successfully.'  
            ]);

        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()  
            ]);
        }
    }
    public function edit($id , Request $request){

        $page = Page::find($id);
        if(empty($page))
        {
            return redirect()->route('page.list');
        }

        return view('admin.page.edit',compact('page'));
        
    }
    public function update($id, Request $request){

        $model = Page::find($id);
        if(empty($model))
        {
            $request->session()->flash('error','Page not found.');
            return response()->json([
                'status'=>false,
                'notFound'=>true,
                'message'=>'Page not found.'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:pages,slug,'.$model->id.',id',
        ]);

        if($validator->passes()){

            $model->name = $request->name;
            $model->slug = $request->slug;
            $model->content = $request->content;
            $model->save();

            // $oldImage = $model->image;

            //save image
            // if(!empty($request->image_id)){
            //     $tempImage = TempImage::find($request->image_id);
            //     $extArray = explode('.',$tempImage->name);
            //     $ext = last($extArray);

            //     $newImageName = $model->id .time(). '.'.$ext;
            //     $sPath = public_path().'/temp/'.$tempImage->name;
            //     $dPath = public_path().'/uploads/page/'.$newImageName;
            //     File::copy($sPath,$dPath);

            //     //generate thumb
            //     $dPath = public_path().'/uploads/page/thumb/'.$newImageName;
            //     $img = Image::make($sPath);
            //     // $img->resize(300, 200);
            //     $img->fit(300, 200, function ($constraint) {
            //         $constraint->upsize();
            //     });
            //     $img->save($dPath);

            //     $model->image = $newImageName;
            //     $model->save();

            //     //delete old image
            //     File::delete(public_path().'/uploads/page/thumb/'.$oldImage);
            //     File::delete(public_path().'/uploads/page/'.$oldImage);
                
            // }

            $request->session()->flash('success','Page updated successfully.');
            return response()->json([
                'status'=>true,
                'message'=>'Page updated successfully.'  
            ]);

        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()  
            ]);
        }
    }
    public function destroy($id, Request $request){
        $model = Page::find($id);
        if(empty($model))
        {
            $request->session()->flash('error','Page not found.');
            return response()->json([
                'status'=>true,
                'message'=>'Page not found.'
            ]);
        }

        $model->is_active = 0;
        $model->is_deleted = 1;
        $model->save();

        // File::delete(public_path().'/uploads/page/thumb/'.$model->image);
        // File::delete(public_path().'/uploads/page/'.$model->image);

        $request->session()->flash('success','Page deleted successfully.');

        return response()->json([
            'status'=>true,
            'message'=>'Page deleted successfully.'
        ]);

    }

    public function getSlug($id, Request $request){

        $model = Page::find($id);
        if(empty($model))
        {
            $request->session()->flash('error','Page not found.');
            return response()->json([
                'status'=>false,
                'notFound'=>true,
                'message'=>'Page not found.'
            ]);
        }
    }
}
