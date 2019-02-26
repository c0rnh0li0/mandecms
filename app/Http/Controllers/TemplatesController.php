<?php

namespace App\Http\Controllers;

use App\Template;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Http\Request;
use App\Http\Resources\Template as TemplateResource;

class TemplatesController extends Controller
{
    private $thumbs_path = 'public/template_thumbs';
    private $default_thumb = 'default_thumb.png';

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
            $users = Template::where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('description', 'LIKE', '%' . $search . '%')
                ->orderBy($sort, $dir)->paginate(10);
        }
        else {
            $users = Template::orderBy($sort, $dir)->paginate(10);
        }


        return TemplateResource::collection($users);
    }

    public function all()
    {
        return TemplateResource::collection(Template::all());
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
            'description' => 'nullable',
            'thumb' => 'nullable',
            'file' => 'nullable',
        ];

        $this->validate($request, $rules);

        $template = new Template();

        $template->name = $request->input('name');
        $template->description = $request->input('description');

        // check for template thumb
        $thmb = $this->uploadFileName($request, 'thumb', $this->thumbs_path, $this->default_thumb, $template->thumb);
        if ($thmb)
            $template->thumb = $thmb;

        $template->file = $request->input('file');

        if ($template->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully created template!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error creating template!'
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
        ];

        $this->validate($request, $rules);

        $template = Template::findOrFail($id);

        $template->name = $request->input('name');
        $template->description = $request->input('description');

        // check for template thumb
        $thmb = $this->uploadFileName($request, 'thumb', $this->thumbs_path, $this->default_thumb, $template->thumb);
        if ($thmb)
            $template->thumb = $thmb;

        $template->file = $request->input('file');

        if ($template->save()) {
            return new TemplateResource($template);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error updating template!'
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
        $template = Template::findOrFail($id);

        if ($template->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted template!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting template!'
            ], 201);
        }
    }

    private function uploadFileName(Request $request, $name, $path, $defaultValue, $prop) {
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
                $fileNameToStore = $defaultValue;
                return $fileNameToStore;
            }
        }

        return null;
    }
}
