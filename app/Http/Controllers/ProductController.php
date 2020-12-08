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
        // return response()->json(json_encode(Product::with(['items', 'attrs.items'])->get()));
    }

    public function get(Request $request) {
        $product = Product::where('product_id', $request->product_id)->first();
        return response()->json($product);
    }

    public function add(Request $request) {
        $vProduct = $request->validate([
            'slug' => 'required|unique:products',
            'name' => 'required',
            'cost' => 'nullable',
            'status' => 'nullable',
            'quatity' => 'nullable',
            'edit_flag' => 'nullable',
            'brand' => 'nullable|string|max:30',
            'discription' => 'nullable|string|max:5000',
            'use' => 'nullable|string|max:5000'
        ]);
        // $data = json_decode(urldecode($request->data));
        // $data = json_decode($request->data);
        $productId = DB::table('products')->insertGetId($vProduct);
        $vProduct['id'] = $productId;
        $attrs = [];
        $items = [];
        if (count($request->attrs) > 0 && count($request->items)) {
            $attrs = $request->attrs;
            $items = $request->items;
            for ($i = 0; $i < count($attrs) ; $i++) {
                $attrId = DB::table('product_attrs')->insertGetId(
                    ['product_id' => $productId,
                     'name' => $attrs[$i]['name'],
                     'slug' => $attrs[$i]['slug']
                    ]
                );
                $attrs[$i]['id'] = $attrId;
                $attrItems = $attrs[$i]['items'];
                for ($j = 0; $j < count($attrItems) ; $j++) {
                    $attrItemId = DB::table('product_attr_items')->insertGetId(
                        ['product_attr_id' => $attrId,
                         'name' => $attrItems[$j]['name'],
                         'slug' => $attrItems[$j]['slug'],
                         'can_be_deleted' => $attrItems[$j]['can_be_deleted'],
                        ]
                    );
                    $attrTempId = $attrItems[$j]['id'];
                    $attrItems[$j]['id'] = $attrItemId;
                    for ($k = 0 ; $k < count($items) ; $k++ ) {
                        if ($i === 0 && $items[$k]['main_attr_id'] === $attrTempId) {
                            $items[$k]['main_attr_id'] = $attrItemId;
                        } else if ($i === 1 && $items[$k]['sub_attr_id'] === $attrTempId) {
                            $items[$k]['sub_attr_id'] = $attrItemId;
                        }
                    }
                }
                $attrs[$i]['items'] = $attrItems;
            }
            for ($i = 0 ; $i < count($items) ; $i++ ) {
                $itemId = DB::table('product_items')->insertGetId(
                    ['product_id' => $productId,
                     'main_attr_id' => $items[$i]['main_attr_id'],
                     'sub_attr_id' => $items[$i]['sub_attr_id'],
                     'cost' => $items[$i]['cost'],
                     'quatity' => $items[$i]['quatity']
                    ]
                );
                $items[$i]['id'] = $itemId;
            }
        }
        $vProduct['attrs'] = $attrs;
        $vProduct['items'] = $items;
        return response()->json($vProduct);
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
