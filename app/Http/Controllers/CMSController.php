<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Page;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\Http\Resources\Page as PageResource;
use App\Http\Resources\Category as CategoryResource;

class CMSController extends Controller
{
    // find any page that has slug in the menu table
    // otherwise return 404
    public function slug($slug)
    {
        $page = Menu::where('slug', '=', "/{$slug}")->get();
        if (sizeof($page) == 1 && $page[0]->page_id != null)
            return new PageResource(Page::findOrFail($page[0]->page_id));
        else if (sizeof($page) == 1 && $page[0]->category_id != null)
            return new CategoryResource(Category::findOrFail($page[0]->category_id));
        else
            // TODO: do 404 response
            return response()->json([
                'success' => false,
                'code' => 404
            ], 201);
    }
}
