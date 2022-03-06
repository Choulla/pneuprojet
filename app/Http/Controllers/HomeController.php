<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /** 
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //categories
        $categories=Category::all();
        //new product max 6 DESC
        $newProducts=Product::orderBy('id','DESC')->take(5)->get();
        //prodoct of the first cat
        $pneu_tourisme_products = Category::find(1)->products()->get();
        $data=[
            'categories'=>$categories, 
            'newProducts'=>$newProducts,
            'pneu_tourisme_products'=>$pneu_tourisme_products
        ];
        return view('welcome')->with($data);
    }
}
