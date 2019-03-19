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
        $menu = Menu::where('slug', '=', "/{$slug}")->get();
        if (sizeof($menu) == 1 && $menu[0]->page_id != null)
            return new PageResource(Page::findOrFail($menu[0]->page_id));
        else if (sizeof($menu) == 1 && $menu[0]->category_id != null)
            return new CategoryResource(Category::findOrFail($menu[0]->category_id));
        else {
            $page = Page::where('url', '=', "/{$slug}")->get();
            if (sizeof($page) == 1 && $page[0]->id != null) {
                return new PageResource($page[0]);
            }
            else {
                // TODO: do 404 response
                return response()->json([
                    'success' => false,
                    'code' => 404
                ], 201);
            }
        }
    }
}
