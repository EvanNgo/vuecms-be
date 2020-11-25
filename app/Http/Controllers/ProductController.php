<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index() {
        return response()->json(Product::with(['items', 'attrs.items'])->get());
    }

    public function get(Request $request) {
        $product = Product::where('product_id', $request->product_id)->first();
        return response()->json($product);
    }

    public function add(Request $request) {
        $data = json_decode(urldecode($request->data));
        return response()->json($data);
        // $request->request->add(['product_id' => Str::random(32)]);
        // $validatedData = $request->validate([
        //     'product_id' => 'required|unique:products|max:32',
        //     'name' => 'required',
        //     'cost' => 'required',
        //     'quatity' => 'required',
        //     'brand' => 'nullable|string|max:30',
        //     'discription' => 'nullable|string|max:5000',
        //     'use' => 'nullable|string|max:5000'
        // ]);
        // $mProduct = Product::create($validatedData);
        // if (count($request->items) === 0) {
        //     return response()->json($mProduct);
        // } else {

        // }
    }

    public function delete(Request $request)
    {
        $product = Product::where('product_id', $request->product_id)->update(['delete_flag' => 1]);
        return response()->json($product);
    }

    public function update(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required',
            'cost' => 'required',
            'quatity' => 'required',
            'brand' => 'nullable|string|max:30',
            'discription' => 'nullable|string|max:5000',
            'use' => 'nullable|string|max:5000'
        ]);
        $productId = $request['product_id'];
        $product = Product::where('product_id', $productId)->update($validatedData);
        return response()->json($product);
    }

    public function forceDelete(Request $request) {
        $product = Product::where('product_id', $request->product_id)->delete();
        return response()->json($product);
    }
}
