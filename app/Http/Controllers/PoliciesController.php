<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\Http\Resources\Policy as PolicyResource;
use App\Policy;

class PoliciesController extends Controller
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
            $policies = Policy::where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('description', 'LIKE', '%' . $search . '%')
                ->orderBy($sort, $dir)->paginate(10);
        }
        else {
            $policies = Policy::orderBy($sort, $dir)->paginate(10);
        }


        return PolicyResource::collection($policies);
    }

    public function all()
    {
        return PolicyResource::collection(Policy::all());
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
        ];

        $this->validate($request, $rules);

        $policy = new Policy();

        $policy->name = $request->input('name');
        $policy->description = $request->input('description');

        if ($policy->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully created policy!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error creating policy!'
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

        $policy = Policy::findOrFail($id);

        $policy->name = $request->input('name');
        $policy->description = $request->input('description');

        if ($policy->save())
        {
            return new PolicyResource($policy);
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
        $policy = Policy::findOrFail($id);

        if ($policy->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted policy!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting policy!'
            ], 201);
        }
    }
}
