<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Page;
use App\Category;
use App\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\Http\Resources\Page as PageResource;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\Gallery as GalleryResource;

class CMSController extends Controller
{
    // find any page that has slug in the menu table
    // otherwise return 404
    public function slug($slug)
    {
        $menu = Menu::where('slug', '=', "/{$slug}")->get();
        if (sizeof($menu) == 1 && $menu[0]->page_id != null) {
            $page = Page::find($menu[0]->page_id);
            $pageResource = new PageResource($page);
            $pageResource->additional([
                'related' => PageResource::collection(Page::where('category_id', '=', $page->category_id)->where('id', '<>', $page->id)->get())
            ]);

            return $pageResource;
        }
        else if (sizeof($menu) == 1 && $menu[0]->category_id != null)
            return new CategoryResource(Category::findOrFail($menu[0]->category_id));
        else if (sizeof($menu) == 1 && $menu[0]->gallery_id != null)
            return new GalleryResource(Gallery::findOrFail($menu[0]->gallery_id));
        else {
            $page = Page::where('url', '=', "/{$slug}")->get();
            if (sizeof($page) == 1 && $page[0]->id != null) {
                $pageResource = new PageResource($page[0]);
                $pageResource->additional([
                    'related' => PageResource::collection(Page::where('category_id', '=', $page[0]->category_id)->where('id', '<>', $page[0]->id)->get())
                ]);

                return $pageResource;
            }
            else {
                $category = Category::where('url', '=', "/{$slug}")->get();
                if (sizeof($category) == 1 && $category[0]->id != null) {
                    $categoryResource = new CategoryResource($category[0]);
                    /*$pageResource->additional([
                        'related' => PageResource::collection(Page::where('category_id', '=', $category[0]->category_id)->where('id', '<>', $category[0]->id)->get())
                    ]);*/

                    return $categoryResource;
                }
            }

            // TODO: do 404 response
            return response()->json([
                'success' => false,
                'code' => 404
            ], 201);
        }
    }
}
