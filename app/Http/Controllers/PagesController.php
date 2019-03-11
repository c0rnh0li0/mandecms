<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\Http\Resources\Page as PageResource;

class PagesController extends Controller
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
            $pages = Page::where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('description', 'LIKE', '%' . $search . '%')
                ->orderBy($sort, $dir)->paginate(10);
        }
        else {
            $pages = Page::orderBy($sort, $dir)->paginate(10);
        }


        return PageResource::collection($pages);
    }

    public function all()
    {
        return PageResource::collection(Page::all());
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
            'body' => 'required',
        ];

        $this->validate($request, $rules);

        $page = new Page();

        $page->title = $request->input('title');
        $page->description = $request->input('description');
        $page->body = $request->input('body');
        $page->template_id = $request->input('template_id');
        $page->category_id = $request->input('category_id');
        $page->user_id = $request->user('api')->id;
        $page->url = $request->input('url');
        $page->hero_image = $this->uploadFileName($request, 'hero_image', $this->hero_path, $page->hero_image);

        if ($page->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully created page!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error creating page!'
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
            'body' => 'required',
        ];

        $this->validate($request, $rules);

        $page = Page::findOrFail($id);

        $page->title = $request->input('title');
        $page->description = $request->input('description');
        $page->body = $request->input('body');
        $page->template_id = $request->input('template_id');
        $page->category_id = $request->input('category_id');
        $page->url = $request->input('url');
        $page->hero_image = $this->uploadFileName($request, 'hero_image', $this->hero_path, $page->hero_image);

        if ($page->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully updated page!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error updating page!'
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
        $page = Page::findOrFail($id);

        if ($page->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted page!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting page!'
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
