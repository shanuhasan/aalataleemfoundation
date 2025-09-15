<?php

namespace App\Http\Controllers\Admin;

use App\Models\Team;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    public function index(Request $request)
    {

        $teams = Team::latest()->where(['is_active' => 1, 'is_deleted' => 0]);

        if (!empty($request->get('keyword'))) {
            $teams = $teams->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $teams = $teams->paginate(10);

        return view('admin.teams.index', compact('teams'));
    }
    public function create()
    {
        return view('admin.teams.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'email' => 'required',
            'mobile' => 'required',
            'designation' => 'required',
        ]);

        if ($validator->passes()) {

            $model = new Team();
            $model->guid = GUIDv4();
            $model->name = $request->name;
            $model->email = $request->email;
            $model->mobile = $request->mobile;
            $model->designation = $request->designation;
            $model->social_link_1 = $request->social_link_1;
            $model->social_link_2 = $request->social_link_2;
            $model->social_link_3 = $request->social_link_3;
            $model->social_link_4 = $request->social_link_4;
            $model->created_by = Auth::user()->id;
            $model->save();

            //save image
            if (!empty($request->image_id)) {
                $media = Media::find($request->image_id);
                $extArray = explode('.', $media->name);
                $ext = last($extArray);

                $newImageName = $model->id . time() . '.' . $ext;
                $sPath = public_path() . '/media/' . $media->name;
                $dPath = public_path() . '/uploads/teams/' . $newImageName;
                File::copy($sPath, $dPath);

                //generate thumb
                // $dPath = public_path().'/uploads/company/thumb/'.$newImageName;
                // $img = Image::make($sPath);
                // // $img->resize(300, 200);
                // $img->fit(300, 200, function ($constraint) {
                //     $constraint->upsize();
                // });
                // $img->save($dPath);

                $model->media_id = $newImageName;
                $model->save();
            }

            $request->session()->flash('success', 'Team added successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Team added successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($guid, Request $request)
    {

        $team = Team::findByGuid($guid);

        if (empty($team)) {
            return redirect()->route('admin.team.index');
        }

        return view('admin.teams.edit', compact('team'));
    }
    public function update($guid, Request $request)
    {
        $model = Team::findByGuid($guid);
        if (empty($model)) {
            $request->session()->flash('error', 'Team not found.');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Team not found.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'email' => 'required',
            'mobile' => 'required',
            'designation' => 'required',
            // 'slug' => 'required|unique:blogs,slug,' . $model->id . ',id',
        ]);

        if ($validator->passes()) {

            $model->name = $request->name;
            $model->email = $request->email;
            $model->mobile = $request->mobile;
            $model->designation = $request->designation;
            $model->social_link_1 = $request->social_link_1;
            $model->social_link_2 = $request->social_link_2;
            $model->social_link_3 = $request->social_link_3;
            $model->social_link_4 = $request->social_link_4;
            $model->status = $request->status;
            $model->save();

            $oldImage = $model->media_id;

            //save image
            if (!empty($request->image_id)) {
                $media = Media::find($request->image_id);
                $extArray = explode('.', $media->name);
                $ext = last($extArray);

                $newImageName = $model->id . time() . '.' . $ext;
                $sPath = public_path() . '/media/' . $media->name;
                $dPath = public_path() . '/uploads/teams/' . $newImageName;
                File::copy($sPath, $dPath);

                //generate thumb
                // $dPath = public_path().'/uploads/company/thumb/'.$newImageName;
                // $img = Image::make($sPath);
                // // $img->resize(300, 200);
                // $img->fit(300, 200, function ($constraint) {
                //     $constraint->upsize();
                // });
                // $img->save($dPath);

                $model->media_id = $newImageName;
                $model->save();

                //delete old image
                // File::delete(public_path().'/uploads/company/thumb/'.$oldImage);
                File::delete(public_path() . '/uploads/teams/' . $oldImage);
            }

            $request->session()->flash('success', 'Team updated successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Team updated successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy($guid, Request $request)
    {
        $model = Team::findByGuid($guid);
        if (empty($model)) {
            $request->session()->flash('error', 'Team not found.');
            return response()->json([
                'status' => true,
                'message' => 'Team not found.'
            ]);
        }

        $model->is_active = 0;
        $model->is_deleted = 1;
        $model->save();

        // File::delete(public_path().'/uploads/page/thumb/'.$model->image);
        // File::delete(public_path().'/uploads/page/'.$model->image);

        $request->session()->flash('success', 'Team deleted successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Team deleted successfully.'
        ]);
    }
}
