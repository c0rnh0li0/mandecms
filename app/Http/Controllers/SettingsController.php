<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Settings as SettingsResource;
use App\Settings;

class SettingsController extends Controller
{
    private $logo_path = 'public/img';
    private $default_logo = 'default_logo.png';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Settings::find(1);

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

        $settings = new Settings();

        //$settings->site_logo = $request->input('site_logo');
        $settings->site_name = $request->input('site_name');
        $settings->site_metatags = $request->input('site_metatags');
        $settings->site_description = $request->input('site_description');
        $settings->facebook_url = $request->input('facebook_url');
        $settings->instagram_url = $request->input('instagram_url');
        $settings->twitter_url = $request->input('twitter_url');
        $settings->contact_email = $request->input('contact_email');

        // TODO: save tags

        // check for site logo
        $logo = $this->uploadFileName($request, 'site_logo', $this->logo_path, $this->default_logo, $settings->site_logo);
        if ($logo)
            $settings->site_logo = $logo;

        $settings->save();

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
        $settings = Settings::find(1);

        //$settings->site_logo = $request->input('site_logo');
        $settings->site_name = $request->input('site_name');
        $settings->site_metatags = $request->input('site_metatags');
        $settings->site_description = $request->input('site_description');
        $settings->facebook_url = $request->input('facebook_url');
        $settings->instagram_url = $request->input('instagram_url');
        $settings->twitter_url = $request->input('twitter_url');
        $settings->contact_email = $request->input('contact_email');

        // check for site logo
        $logo = $this->uploadFileName($request, 'site_logo', $this->logo_path, $this->default_logo, $settings->site_logo);
        if ($logo)
            $settings->site_logo = $logo;

        if ($settings->save())
        {
            return new SettingsResource($settings);
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
