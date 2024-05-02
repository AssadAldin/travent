<?php

namespace App\Http\Controllers;

use App\Models\ChatsappAuth;
use App\Models\WhatsappModel;
use Illuminate\Http\Request;

class WhatsappController extends Controller
{
    public function config()
    {
        $whatsapp = ChatsappAuth::find(1);
        return view('whatsappConfig')->with('whatsapp', $whatsapp);
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'app_key' => 'required',
            'auth_key' => 'required',
            'open' => 'required',
            'g_ar' => 'required',
            'g_en' => 'required',
            'h_ar' => 'required',
            'h_en' => 'required',
            'a_ar' => 'required',
            'a_en' => 'required',
        ]);

        $whatsapp = ChatsappAuth::find(1);
        $whatsapp->app_key = $request->input('app_key');
        $whatsapp->auth_key = $request->input('auth_key');
        $whatsapp->open = $request->input('open');
        $whatsapp->g_ar = $request->input('g_ar');
        $whatsapp->g_en = $request->input('g_en');
        $whatsapp->h_ar = $request->input('h_ar');
        $whatsapp->h_en = $request->input('h_en');
        $whatsapp->a_ar = $request->input('a_ar');
        $whatsapp->a_en = $request->input('a_en');

        $whatsapp->save();

        return back()->with('success', 'Updated successfully');
    }
}
