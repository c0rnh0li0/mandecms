<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\Http\Resources\Menu as MenuResource;
use App\Menu;

class MenusController extends Controller
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
            $menus = Menu::where('name', 'LIKE', '%' . $search . '%')
                ->orderBy($sort, $dir)->paginate(10);
        }
        else {
            $menus = Menu::orderBy($sort, $dir)->paginate(10);
        }


        return MenuResource::collection(Menu::all());
    }

    public function all()
    {
        return MenuResource::collection(Menu::all());
    }

    public function sort(Request $request)
    {
        $sorted = \GuzzleHttp\json_decode($request->input('sorted'));
        $this->updateSortOrder($sorted);

        return response()->json([
            'success' => true,
            'message' => 'Menu order sucessfully saved!'
        ], 201);
    }

    protected function updateSortOrder($items, $parent = null, $order = 0)
    {
        foreach ($items as $item) {
            $menu = Menu::findOrFail($item->id);
            $menu->order = $order++;
            $menu->parent_id = $parent;
            $menu->save();

            if (sizeof($item->children) > 0)
                $this->updateSortOrder($item->children, $item->id);
        }
    }

    public function build()
    {
        return MenuResource::collection($this->generate());
    }

    public function generate($parent = null)
    {
        $menu = Menu::where('parent_id', '=', $parent)->where('visible', '=', 1)->orderBy('order')->get();

        foreach ($menu as $mitem) {
            $mitem->children = $this->generate($mitem->id);
        }

        return $menu;
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
            'slug' => 'required',
            'visible' => 'required',
        ];

        $this->validate($request, $rules);

        $menu = new Menu();

        $menu->name = $request->input('name');
        $menu->parent_id = $request->input('parent_id', 'NULL');
        $menu->order = $this->getNextOrder($request->input('parent_id'));
        $menu->slug = $request->input('slug');
        $menu->visible = $request->input('visible') == true ? 1 : 0;
        $menu->page_id = $request->input('page_id');
        $menu->category_id = $request->input('category_id');

        if ($menu->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully created menu item!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error creating menu item!'
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
            'slug' => 'required',
            'visible' => 'required',
        ];

        $this->validate($request, $rules);

        $menu = Menu::findOrFail($id);

        $menu->name = $request->input('name');
        //$menu->parent_id = $request->input('parent_id', 'NULL');
        //$menu->order = $request->input('order');
        $menu->slug = $request->input('slug');
        $menu->visible = $request->input('visible') == true ? 1 : 0;
        $menu->page_id = $request->input('page_id');
        $menu->category_id = $request->input('category_id');

        if ($menu->save()) {
            return new MenuResource($menu);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error updating menu item!'
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
        $menu = Menu::findOrFail($id);

        if ($menu->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted menu item!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting menu item!'
            ], 201);
        }
    }

    private function getNextOrder($parent) {
        $currentMax = Menu::where('parent_id', '=', $parent)->max('order');
        //var_dump($currentMax);
        //die;


        if ($currentMax == null)
            return 0;

        //$currentMax = (int)$currentMax;
        return (int) $currentMax + 1;
    }
}
