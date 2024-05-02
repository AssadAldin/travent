<?php

namespace App\Http\Controllers;

use App\Models\MobileNav;
use Illuminate\Http\Request;

class MobileNavController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            [
                'name' => __('Mobile Navbar'),
                'url' => route('boat.admin.index')
            ],
            [
                'name' => __('All'),
                'class' => 'active'
            ],
        ];
        $items = MobileNav::orderBy('order', 'asc')->get();
        return view('mobileNav.index', compact(['breadcrumbs', 'items']));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'text' => 'required',
            'icon' => 'required',
            'size' => 'required',
            'url' => 'required',
            'order' => 'required',
            'type' => 'required',
        ]);

        // New item
        $icon = new MobileNav;
        $icon->text = $request->input('text');
        $icon->icon = $request->input('icon');
        $icon->size = $request->input('size');
        $icon->url = $request->input('url');
        $icon->order = $request->input('order');
        $icon->type = $request->input('type');
        $icon->save();

        return back()->with('success', __('You add new item to mobile navbar'));
    }

    public function update(Request $request, MobileNav $icon)
    {
        $this->validate($request, [
            'text' => 'required',
            'icon' => 'required',
            'size' => 'required',
            'url' => 'required',
            'order' => 'required',
            'type' => 'required',
        ]);

        $icon->text = $request->input('text');
        $icon->icon = $request->input('icon');
        $icon->size = $request->input('size');
        $icon->url = $request->input('url');
        $icon->order = $request->input('order');
        $icon->type = $request->input('type');
        $icon->save();

        return back()->with('success', __('You update item in mobile navbar'));
    }

    public function destroy(MobileNav $item)
    {
        //Check if post exists before deleting
        if (!isset($item)) {
            return redirect()->back()->with('error', __('Item is not found'));
        }
        $item->delete();
        return back()->with('success', __('You delete item in mobile navbar'));
    }
}
