<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Settings as SettingsResource;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\Settings;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Settings::findOrFail(1);
        return new SettingsResource($settings);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $settings = Settings::find(1);
        if (sizeof($settings) == 1) {
            return;
        }

        Settings::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Successfully created settings!'
        ], 201);
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
        $settings = Settings::findOrFail(1);

        if ($settings->fill($request->all())->save())
        {
            return new SettingsResource($settings);
        }
    }
}
