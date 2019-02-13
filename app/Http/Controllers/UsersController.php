<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Resources\User as UserResource;
use App\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sort = Request::get('sort');
        $dir = Request::get('direction');

        if ($sort == '')
            $sort = 'created_at';

        $search = Request::get('q');
        if ($search != '') {
            $users = User::where('name', 'LIKE', '%' . $search . '%')
                         ->orWhere('email', 'LIKE', '%' . $search . '%')
                         ->orderBy($sort, $dir)->paginate(10);
        }
        else {
            $users = User::orderBy($sort, $dir)->paginate(10);
        }


        return UserResource::collection($users);
    }

    public function info() {
        dd(auth('api'));
        $user = User::findOrFail(auth('api')->user()->id);

        return new UserResource($user);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*$request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if ($user->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully created user!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error creating user!'
            ], 201);
        }*/
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'user_role' => 'required',
            'user_avatar' => 'image|nullable|max:1999'
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->user_role = $request->input('user_role');

        // handle file upload
        if ($request->hasFile('user_avatar')) {
            // get just filename
            $filename = pathinfo($request->file('user_avatar')->getClientOriginalName(), PATHINFO_FILENAME);
            // get just extension
            $extension = $request->file('user_avatar')->getClientOriginalExtension();
            // filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // upload
            $path = $request->file('user_avatar')->storeAs('public/user_avatars', $fileNameToStore);
        }
        else {
            $fileNameToStore = 'default_avatar.png';
        }

        $user->user_avatar = $fileNameToStore;

        if ($user->save())
        {
            return new UserResource($user);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted user!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user!'
            ], 201);
        }
    }
}
