<?php

namespace App\Http\Controllers\Backend\Report\POS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\OrderList\OrderList;
use App\Repositories\Backend\Report\ReportRepository;

class ReportController extends Controller
{
    public function index(){
        $from   = date('Y-m-d');
        $to     = date('Y-m-d');

        $orders = Order::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->get();

    	return view('backend.report.pos.sale.index', compact('orders', 'from', 'to'));
    }

    public function store(Request $request){
        $from   = date('Y-m-d', strtotime($request->from));
        $to     = date('Y-m-d', strtotime($request->to));

        $orders = Order::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->get();

        return view('backend.report.pos.sale.index', compact('orders', 'from', 'to'));
    }

    public function show($id){
    	$order = Order::findOrFail($id);

    	return view('backend.report.pos.sale.show', compact('order'));
    }

    public function destroy($id){
    	$order = Order::findOrFail($id);

    	foreach ($order->order_list as $list) {
    		$list->delete();
    	}

    	$order->delete();

    	return redirect()->route('admin.report.pos.sale.index');
    }
}
