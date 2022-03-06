<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomController extends Controller
{
    public function index(){
        $orders=Order::orderBy('created_at','DESC')->paginate(10);

      //  $orders = Order::all()->paginate(6);
        return view('vendor.voyager.orders.browse')->with('orders',$orders);
    }

    public function update(Request $request)
    {
        //order to update
        $order=Order::find($request->order_id);
        $order->statut = $request->newstatut;
        $order->save();
        return redirect()->back()->with('success','la commande a été modifié avec succes');
    }

    public function getOrderDetails($id)
    {
        //get order
        $order = Order::find($id);
        return view('vendor.voyager.orders.detail')->with('order',$order);
    }

}
