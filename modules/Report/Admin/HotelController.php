<?php
namespace Modules\Report\Admin;

use Illuminate\Http\Request;
use Modules\AdminController;
use Modules\Hotel\Models\Hotel;

class HotelController extends AdminController
{
    public function index(Request $request)
    {
        if ($request->from) {
            $from = $request->from;
        } else {
            $from = date('Y-m-d');
        }
        if ($request->to) {
            $to = $request->to;
        } else {
            $to = date('Y-m-d');
        }
        if ($request->status) {
            $status = $request->status;
        } else {
            $status = 'all';
        }
        $from = $request->from;
        $to = $request->to;
        if ($request->from and $request->to) {
            if ($status != 'all') {
                $data = Hotel::where('status', $status)
                    ->whereRaw(
                        "(created_at >= ? AND created_at <= ?)",
                        [
                            $from . " 00:00:00",
                            $to . " 23:59:59"
                        ]
                    )
                    ->orderBy('created_at', 'asc')->get();
            } else {
                $data = Hotel::whereRaw(
                    "(created_at >= ? AND created_at <= ?)",
                    [
                        $from . " 00:00:00",
                        $to . " 23:59:59"
                    ]
                )
                    ->orderBy('created_at', 'asc')->get();
            }

        } else {
            if ($status != 'all') {
                $data = Hotel::where('status', $status)->orderBy('created_at', 'asc')->get();
            } else {
                $data = Hotel::orderBy('created_at', 'asc')->get();
            }
        }
        return view('Report::admin.hotels.index', compact('data', 'status'));
    }
}
