<?php

namespace App\Http\Controllers\Frontend\Sale;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Order\Order;
use App\Models\OrderList\OrderList;
use App\Models\Inventory\Inventory;
use App\Models\ProductSize\ProductSize;
use App\Models\Notification\Notification;

use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Volume;

use Auth;
use DB;

class SaleController extends Controller
{
    public function index(){
        $total  = 0;
        $orders = Order::where(Db::raw('date(created_at)'), date('Y-m-d'))
                  ->where('user_id', Auth::user()->id)
                  ->orderBy('created_at', 'desc')
                  ->get();

        foreach ($orders as $order) {
            $total = $total + $order->payable;
        }

        return view('frontend.user.sale.daily', compact('total', 'orders'));
    }

    public function monthly(){
        $total  = 0;
        $date   = date('F Y');
        $orders = Order::whereBetween(Db::raw('date(created_at)'), [date('Y-m-01'), date('Y-m-31')])
                  ->where('user_id', Auth::user()->id)
                  ->orderBy('created_at', 'desc')
                  ->get();

        foreach ($orders as $order) {
            $total = $total + $order->payable;
        }

        return view('frontend.user.sale.monthly', compact('total', 'orders', 'date'));
    }

    public function save(Request $request){
    	$arr 	 = json_decode($request->orders);
        $p_avail = 0; //available product

        for($i = 0; $i < count($arr); $i++)
        {
              $status = $this->product_availability($arr[$i]->id, $arr[$i]->qty);

              if($status == 'Available')
                $p_avail++;
        }

        //
        // check if ordered products is equals to available products
        //
        if(count($arr) == $p_avail)
        {
            $order  = new Order();
            $order->transaction_no  = date('y-m').rand(100000, 999999);
            $order->cash            = $request->cash;
            $order->change          = $request->change;
            $order->payable         = $request->payable;
            $order->discount        = $request->discount;
            $order->user_id         = Auth::user()->id;
            $order->save();
            //

            // set stock use
            //
            for($i = 0; $i < count($arr); $i++)
            {
                $size = ProductSize::where('size', $arr[$i]->size)->first();

                $list = new OrderList();
                $list->order()->associate($order);
                $list->product_id         = $arr[$i]->id;
                $list->price              = $arr[$i]->price;
                $list->quantity           = $arr[$i]->qty;
                $list->product_size_id    = $size->id;
                $list->save();

                $product      = Product::findOrFail($list->product_id);
                $product_size = $product->product_size;

                foreach ($product_size as $size) {
                    $inventories = $size->ingredients;

                    foreach($inventories as $inventory){
                        $qty_left  = 0;
                        $stock_dec = 0;

                        if($inventory->physical_quantity == 'Mass')
                        {
                            $stock_qty = new Mass($inventory->stock, $inventory->unit_type);

                            $req_qty   = new Mass(($list->quantity * $inventory->pivot->quantity), $inventory->pivot->unit_type);

                            $qty_left  = $stock_qty->subtract($req_qty);

                            $stock_dec = $qty_left->toUnit($inventory->unit_type);
                        }
                        elseif($inventory->physical_quantity == 'Volume')
                        {
                            $stock_qty = new Volume($inventory->stock, $inventory->unit_type);

                            $req_qty   = new Volume(($list->quantity * $inventory->pivot->quantity), $inventory->pivot->unit_type);

                            $qty_left  = $stock_qty->subtract($req_qty);

                            $stock_dec = $qty_left->toUnit($inventory->unit_type);
                        }
                        else
                        {
                            $stock_dec = $list->quantity;
                        }

                        $inventory->stock = $stock_dec  * $list->quantity;
                        $inventory->save();
                    }
                } 
            }

            return ['success', $order->transaction_no];
        }
        else
        {
            $this->notification();
        }
    	

    	return 'failed';
    }

    public function product_availability($product_id, $quantity){
        $product      = Product::findOrFail($product_id);
        $product_size = $product->product_size;
        $status       = 'Not Available';
        $available    = 0;

        foreach ($product_size as $size) 
        {
            $inventories = $size->ingredients;

            foreach($inventories as $inventory)
            {
                $qty_left  = 0;
                $stock_dec = 0;

                if($inventory->physical_quantity == 'Mass')
                {
                    $stock_qty = new Mass($inventory->stock, $inventory->unit_type);

                    $req_qty   = new Mass(($quantity * $inventory->pivot->quantity), $inventory->pivot->unit_type);

                    $qty_left  = $stock_qty->subtract($req_qty);

                    $stock_dec = $qty_left->toUnit($inventory->unit_type);
                }
                elseif($inventory->physical_quantity == 'Volume')
                {
                    $stock_qty = new Volume($inventory->stock, $inventory->unit_type);

                    $req_qty   = new Volume(($quantity * $inventory->pivot->quantity), $inventory->pivot->unit_type);

                    $qty_left  = $stock_qty->subtract($req_qty);

                    $stock_dec = $qty_left->toUnit($inventory->unit_type);
                }
                else
                {
                    $stock_dec = $quantity;
                }

                if($stock_dec > 0)
                    $available++;
            }

            if(count($inventories) == $available)
                $status = 'Available';
        }

        return $status;
    }

    public function notification(){
        $inventories = Inventory::whereRaw('stock < reorder_level')->get();

        foreach ($inventories as $inventory) {
            $desc = $inventory->name.' has '.$inventory->stock.' stocks left.';

            Notification::updateOrCreate(
                [
                    'date' => date('Y-m-d'), 
                    'description' => $desc,
                    'status' => 'new'
                ],
                [
                    'inventory_id' => $inventory->id
                ]
            ); 
        }

        // return $inventories;
    }
}
