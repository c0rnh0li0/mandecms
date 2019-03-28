<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\Http\Resources\Gallery as GalleryResource;
use App\Gallery;

class GalleriesController extends Controller
{
    private $hero_path = 'public/hero_images';

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
            $galleries = Gallery::where('name', 'LIKE', '%' . $search . '%')
                ->where('is_gallery', '=', 1)
                ->orWhere('description', 'LIKE', '%' . $search . '%')
                ->orderBy($sort, $dir)->paginate(10);
        }
        else {
            $galleries = Gallery::where('is_gallery', '=', 1)->orderBy($sort, $dir)->paginate(10);
        }


        return GalleryResource::collection($galleries);
    }

    public function all()
    {
        return GalleryResource::collection(Gallery::where('is_gallery', '=', 1)->get());
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
            'url' => 'required',
            'hero_image' => 'image|nullable|max:1999'
        ];

        $this->validate($request, $rules);

        $gallery = new Gallery();

        $gallery->title = $request->input('title');
        $gallery->description = $request->input('description');
        $gallery->url = $request->input('url');
        $gallery->created_by = $request->user('api')->id;
        $gallery->is_gallery = 1;
        $gallery->hero_image = $this->uploadFileName($request, 'hero_image', $this->hero_path, $gallery->hero_image);

        if ($gallery->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully created gallery!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error creating gallery!'
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
            'url' => 'required',
            'hero_image' => 'image|nullable|max:1999'
        ];

        $this->validate($request, $rules);

        $gallery = Gallery::findOrFail($id);

        $gallery->title = $request->input('title');
        $gallery->description = $request->input('description');
        $gallery->url = $request->input('url');
        $gallery->is_gallery = 1;
        $gallery->hero_image = $this->uploadFileName($request, 'hero_image', $this->hero_path, $gallery->hero_image);

        if ($gallery->save()) {
            return new CategoryResource($gallery);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error updating category!'
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
        $gallery = Gallery::findOrFail($id);

        if ($gallery->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted category!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting category!'
            ], 201);
        }
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
