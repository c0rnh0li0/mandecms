<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Image as ImageResource;
use App\Image;

class ImagesController extends Controller
{
    private $images_path = 'public/galleries';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'title' => 'required',
            'path' => 'image|nullable|max:1999',
            'gallery_id' => 'required'
        ];

        $this->validate($request, $rules);

        $image = new Image();

        $image->title = $request->input('title');
        $image->description = $request->input('description');
        $image->path = $this->uploadFileName($request, 'path', $this->images_path, $image->path);
        $image->gallery_id = $request->input('gallery_id');

        if ($image->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully created image!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error creating image!'
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
            'title' => 'required',
            'path' => 'image|nullable|max:1999'
        ];

        $this->validate($request, $rules);

        $image = Image::findOrFail($id);

        $image->title = $request->input('title');
        $image->description = $request->input('description');
        $image->path = $this->uploadFileName($request, 'path', $this->images_path, $image->path);

        if ($image->save()) {
            return new ImageResource($image);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error updating image!'
            ], 201);
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
        $image = Image::findOrFail($id);

        Storage::delete('public/galleries/' . $image->path);

        if ($image->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted image!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting image!'
            ], 201);
        }
    }

    public function all($gallery_id) {
        $images = Image::where('gallery_id', '=', $gallery_id)->get();
        return ImageResource::collection($images);
    }

    private function uploadFileName(Request $request, $name, $path, $prop) {
        // handle file upload
        if ($request->hasFile($name) && $request->file($name) != null) {
            // get just filename
            $filename = pathinfo($request->file($name)->getClientOriginalName(), PATHINFO_FILENAME);
            // get just extension
            $extension = $request->file($name)->getClientOriginalExtension();
            // filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // upload
            $filepath = $request->file($name)->storeAs($path, $fileNameToStore);

            return $fileNameToStore;
        }
        else {
            if ($prop == '' || $prop == null) {
                return null;
            }
            else
                return $prop;
        }
    }
}
