<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Hash;
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
        $sort = FacadesRequest::get('sort');
        $dir = FacadesRequest::get('direction');

        if ($sort == '')
            $sort = 'created_at';

        $search = FacadesRequest::get('q');
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
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'role_id' => 'required|integer',
            'user_avatar' => 'image|nullable|max:1999'
        ];

        $this->validate($request, $rules);

        $user = new User();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role_id = $request->input('role_id');


        $password = str_random(10);
        $user->password = Hash::make($password);


        // handle file upload
        if ($request->hasFile('user_avatar') && $request->file('user_avatar') != null) {
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

        // TODO: implement send password email with password
        /*Mail::send('emails.reminder', ['user' => $user], function ($m) use ($user) {
            $m->from('aaaaaag@hotmail.com', 'a Your Application');

            $m->to($user->email, $user->name)->subject('Your Reminder!');
        });*/
        if ($user->save()) {
            return response()->json([
                'success' => true,
                'password' => $password,
                'message' => 'Successfully created user!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error creating user!'
            ], 201);
        }
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
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'role_id' => 'required|integer',
            'user_avatar' => 'image|max:1999|nullable'
        ];

        $this->validate($request, $rules);

        $user = User::findOrFail($id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role_id = $request->input('role_id');

        // handle file upload
        if ($request->hasFile('user_avatar') && $request->file('user_avatar') != null) {
            // get just filename
            $filename = pathinfo($request->file('user_avatar')->getClientOriginalName(), PATHINFO_FILENAME);
            // get just extension
            $extension = $request->file('user_avatar')->getClientOriginalExtension();
            // filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // upload
            $path = $request->file('user_avatar')->storeAs('public/user_avatars', $fileNameToStore);

            $user->user_avatar = $fileNameToStore;
        }
        else {
            if ($user->user_avatar == '' || $user->user_avatar == null) {
                $fileNameToStore = 'default_avatar.png';
                $user->user_avatar = $fileNameToStore;
            }
        }

        if ($user->save())
        {
            return new UserResource($user);
        }
    }

    public function password(Request $request, $id) {
        $user = User::findOrFail($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'oldPassword' => 'You are not logged in'
                ]
            ], 422);
        }

        if (!Hash::check($request->oldPassword, $user->password)) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'oldPassword' => 'Incorrect password'
                ]
            ], 422);
        }
        else {
            $user->password = Hash::make($request->newPassword);
            if ($user->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password successfully changed'
                ], 201);
            }
            else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating password'
                ], 201);
            }
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
