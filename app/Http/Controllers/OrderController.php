<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderProduct;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use PDF;

class OrderController extends Controller
{
    //
    public function index(){
        $orders = auth()->user()->orders()->with('products')->orderBy('created_at','DESC')->paginate(10);
   // dd($orders);
        return view('auth.orders')->with('orders',$orders);
    }

    public function store(Request $request){
        //insert into order orders table
        $totale=totalAfterReduction(getPrice(Cart::subtotal()) , Auth::user()->reduction);
        $order = Order::create([
            'user_id'=>Auth::id(),
            'adresse_complet'=>$request->adresse,
            'codepostale'=>$request->codepostale,
            'tele'=> $request->tele,
            'statut'=>'en cours de traitement',
            'totale'=>$totale
        ]);
        
        //insert into order_product
        foreach (Cart::content() as $product) {
            OrderProduct::create(
                [
                    'order_id'=>$order->id,
                    'product_id'=>$product->model->id,
                    'qantite'=>$product->qty,
                ]
            );
        }
        //export pdf 
        //$pdf=PDF::loadView('layouts.invoice',['order'=>$order]);
        //return $pdf->download('commande_'.$order->id.'.pdf');
        return view('cart/thankyou')->with('order',$order);
    }

    public function generate(Request $request)
    {
        //get order
        $order=Order::find($request->order_id);
        $pdf=PDF::loadView('layouts.invoice',['order'=>$order]);
        Cart::destroy();
        return $pdf->download('commande_'.$order->id.'.pdf');
        //dd($request);
    }

    public function generateTemplate(Request $request)
    {
        //get order
        $order=Order::find($request->order_id);
        $pdf=PDF::loadView('layouts.invoiceg',['order'=>$order]);
        Cart::destroy();
        return $pdf->download('commande_'.$order->id.'.pdf');
        //dd($request);
    }

    public function getOrderDetails($id)
    {
        //get order
        $order = Order::find($id);
        return view('auth.details')->with('order',$order);
    }

}
