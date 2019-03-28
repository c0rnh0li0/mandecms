<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\Http\Resources\Category as CategoryResource;
use App\Category;

class CategoriesController extends Controller
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
            $categories = Category::where('name', 'LIKE', '%' . $search . '%')
                ->where('is_gallery', '=', 0)
                ->orWhere('description', 'LIKE', '%' . $search . '%')
                ->orderBy($sort, $dir)->paginate(10);
        }
        else {
            $categories = Category::where('is_gallery', '=', 0)->orderBy($sort, $dir)->paginate(10);
        }


        return CategoryResource::collection($categories);
    }

    public function all()
    {
        return CategoryResource::collection(Category::where('is_gallery', '=', 0)->get());
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

        $category = new Category();

        $category->title = $request->input('title');
        $category->description = $request->input('description');
        $category->url = $request->input('url');
        $category->created_by = $request->user('api')->id;
        $category->hero_image = $this->uploadFileName($request, 'hero_image', $this->hero_path, $category->hero_image);

        if ($category->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully created category!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error creating category!'
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

        $category = Category::findOrFail($id);

        $category->title = $request->input('title');
        $category->description = $request->input('description');
        $category->url = $request->input('url');
        $category->hero_image = $this->uploadFileName($request, 'hero_image', $this->hero_path, $category->hero_image);

        if ($category->save()) {
            return new CategoryResource($category);
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
        $category = Category::findOrFail($id);

        if ($category->delete()) {
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
