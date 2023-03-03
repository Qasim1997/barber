<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $profile = Profile::all();
        // return response()->json([
        //     'result', $profile
        // ]);
        $user = auth()->guard('api')->user();
        // $profile = Profile::find('created_by', $user->id)
        $profile = Profile::where('created_by', $user->id)->get();

        return response()->json([
            'result' => $profile,
        ]);
    }

    public function post()
    {
        $profile = Post::all();

        return response()->json([
            'result', $profile,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $loggeduser = auth()->guard('api')->user();
        $filename = null;
        if ($request->file('image')) {
            // $filename = $request->file('image')->hashName();
            // $image_path = 'admin_assets/uploads/profile/';
            // $request->image->move(public_path($image_path), $filename);
            $filename = time().'.'.$request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('public/profile', $filename);
            $user = Profile::where('created_by', $loggeduser->id)->first();
            $exists = Storage::disk('public')->exists("profile/{$user->image}");
            if ($exists) {
                Storage::disk('public')->delete("profile/{$user->image}");
            }
        }
        $data = Profile::updateOrCreate(
            ['created_by' => $loggeduser->id],
            ['username' => $request->username, 'gender' => $request->gender, 'age' => $request->age, 'experience' => $request->experience, 'location' => $request->location, 'status' => $request->status, 'image' => $filename]
        );

        return response()->json([
            'message' => 'profile saved successfully',
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $profile = Profile::find($id);
        $user = auth()->guard('api')->user();
        if ($profile) {
            $profile = Profile::find($id);

            return response()->json(['result' => $profile], 200);
        } else {
            return response([
                'message' => 'Profile Data Not Found',
                'status' => 'failed',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $profile = Profile::find($id);
        if ($profile) {
            $filename = null;
            if ($request->file('image')) {
                $filename = $request->file('image')->hashName();
                $image_path = 'admin_assets/uploads/';
                $request->image->move(public_path($image_path), $filename);
            }
            $user = auth()->guard('api')->user();
            $profile = Profile::where('created_by', $id)
                ->update([
                    'username' => $request->username,
                    'gender' => $request->gender,
                    'age' => $request->age,
                    'experience' => $request->experience,
                    'location' => $request->location,
                    'status' => $request->status,
                ]);
            if ($request->file('image')) {
                $profile = Profile::where('created_by', $id)
                    ->update([
                        'image' => $request->filename,
                    ]);
            }
            $profile = Profile::find($id);

            return response([
                'message' => 'Profile edit Successfully',
                'status' => 'success',
            ], 201);
        } else {
            return response([
                'message' => 'Profile Data Not Found',
                'status' => 'failed',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
