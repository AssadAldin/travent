<?php

namespace App\Http\Controllers;

use App\Models\HideShowNav;
use Illuminate\Http\Request;

class HideShowNavController extends Controller
{
    public function toggle(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);
        $hideShowNav = HideShowNav::find(1);
        $hideShowNav->status = !$hideShowNav->status;
        $hideShowNav->save();

        return back()->with('success', __('You update navbar status'));
    }
}
