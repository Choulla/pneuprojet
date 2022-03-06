<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    //
    public function search()
    {
        $q=request()->input('q');
        $products=Product::where('title','like',"%$q%")
                    ->orWhere('description','like',"%$q%")
                    ->get();
        $categories=Category::all();
        $data=[
             'products'=>$products, 
              'categories'=>$categories
         ];            

        return view('products.index')->with($data);
        //dd($q);
    }
    public function filter(Request $request)
    {
        $conditionBody = ['width' => $request->largeur,
                         'height' => $request->hauteur,
                         'diameter' => $request->diametre
        ];
        $products = Product::where($conditionBody)->get();
        $categories=Category::all();
        $data=[
            'products'=>$products, 
            'categories'=>$categories
        ];
        return view('products.index')->with($data);
        //dd($request);
    }











    public function filteradv(Request $request)
    {
        
        if( isset($request->category_id) ){
            //if isset categories , multi or not
          
            $products_ids = DB::table('category_product')->whereIn('category_id', $request->category_id)->get();
            $getProduct_id=$products_ids->all();

            $product_id_array = [];
            for ($i=0; $i < count($getProduct_id); $i++) { 
                $product_id_array[$i]=$getProduct_id[$i]->product_id;
            }
            //product of those cat selected
            $products=Product::whereIn('id',$product_id_array);
            if(isset($request->largeur)){ $products=$products->whereIn('width',$request->largeur); }
            if(isset($request->hauteur)){ $products=$products->whereIn('height',$request->hauteur); }
            if(isset($request->diametre)){ $products=$products->whereIn('diameter',$request->diametre); }
            if(isset($request->marque)){ $products=$products->whereIn('marque',$request->marque); }
            //dd($products);
        }else{
            $products=Product::where('price','>',0);
            if(isset($request->largeur)){ $products=$products->whereIn('width',$request->largeur); }
            if(isset($request->hauteur)){ $products=$products->whereIn('height',$request->hauteur); }
            if(isset($request->diametre)){ $products=$products->whereIn('diameter',$request->diametre); }
            if(isset($request->marque)){ $products=$products->whereIn('marque',$request->marque); }
        }   

        //$products = Category::find($request->id)->products()->where($conditionBody)->get();
        $categories=Category::all();
        $data=[
            'products'=>$products->get(), 
            'categories'=>$categories
        ];
        return view('products.index')->with($data);
        //dd($request);
    }











    public function index(){
         if(request()->categorie){
                $products = Product::with('categories')->whereHas('categories',function ($query) {
                    $query->where('slug', request()->categorie);
                })->orderBy('created_at','desc')->get();
                $categories=Category::all();
                $data=[
                    'products'=>$products, 
                    'categories'=>$categories
                ];
                return view('products.index')->with($data);
                //dd("ok"); 
            }
            else{
                $products=Product::with('categories')->orderBy('created_at','desc')->get();
                $categories=Category::all();
                $data=[
                    'products'=>$products, 
                    'categories'=>$categories
                ];
                return view('products.index')->with($data);
            }
    }
    public function show($slug){
        $product=Product::where('slug',$slug)->firstOrFail();
        //dd($product);
        return view('products.show')->with('product',$product);
    }
}
