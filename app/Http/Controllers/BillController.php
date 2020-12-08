<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\BillSubItem;
use App\Models\Product;
use App\Models\ProductItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function index() {
        return response()->json(Bill::with(['items.sub_items'])->get());
    }

    public function add(Request $request) {
        $vBill = $request->validate([
            'name' => 'required',
            'status' => 'nullable',
            'total_cost' => 'required'
        ]);
        $billId = Bill::create($vBill)->id;
        $items = $request->items;
        for ($i = 0; $i < count($items) ; $i++) {
            $billItemId = BillItem::create(
                ['bill_id' => $billId,
                 'product_id' => $items[$i]['product_id'],
                 'name' => $items[$i]['name'],
                 'cost' => $items[$i]['cost'],
                 'quatity' => $items[$i]['quatity'],
                 'ship_cost' => $items[$i]['ship_cost'],
                 'total_cost' => $items[$i]['total_cost']
                ]
            )->id;
            $items[$i]['id'] = $billItemId;
            for ($j = 0; $j < count($items[$i]['sub_items']) ; $j++) {
                $subItemId = BillSubItem::create(
                    ['bill_item_id' => $billItemId,
                     'product_item_id' => $items[$i]['sub_items'][$j]['product_item_id'],
                     'name' => $items[$i]['sub_items'][$j]['name'],
                     'cost' => $items[$i]['sub_items'][$j]['cost'],
                     'quatity' => $items[$i]['sub_items'][$j]['quatity'],
                     'total_cost' => $items[$i]['sub_items'][$j]['total_cost']
                    ]
                )->id;
                $items[$i]['sub_items'][$j]['id'] = $subItemId;
            }
        }
        $newBill = Bill::with(['items.sub_items'])->find($billId);
        $products = [];
        if ($newBill->status == 3) {
            $items = $newBill->items;
            for ($i = 0; $i < count($items) ; $i++) {
                $mItem = $items[$i];
                $product = Product::find($mItem['product_id']);
                if (count($mItem['sub_items']) == 0) {
                    $product->quatity = $product['quatity'] + $mItem['quatity'];
                    $product->save();
                } else {
                    $product['items'] = [];
                    $subs = $mItem['sub_items'];
                    for ($j = 0 ; $j < count($subs) ; $j++) {
                        $mSub = $subs[$j];
                        $productItem = ProductItem::find($mSub['product_item_id']);
                        $productItem->quatity = $productItem['quatity'] + $mSub['quatity'];
                        $productItem->save();
                        $product['items'] = $productItem;
                    }
                }
                $products[] = $product;
            }
        }
        return response()->json(['bill' => $newBill, 'products' => $products]);
    }

    public function update(Request $request) {
        $items = $request->items;
        $currentBill = Bill::find($request->id);
        // Fix It: need to check null Data
        if ($currentBill->status == 3) {
            return response()->json(['error' => 'Can\'t update bill'], 400);
        }
        
        for ($i = 0 ; $i < count($items) ; $i++) {
            $nItem = $items[$i];
            $subItems = $nItem['sub_items'];
            switch ($nItem['flag']) {
                case 'update':
                    $cItem = BillItem::find($nItem['id']);
                    $cItem->cost = $nItem['cost'];
                    $cItem->name = $nItem['name'];
                    $cItem->quatity = $nItem['quatity'];
                    $cItem->total_cost = $nItem['total_cost'];
                    $cItem->ship_cost = $nItem['ship_cost'];
                    $cItem->save();
                    for ($j = 0; $j < count($subItems) ; $j++) {
                        $nSubItem = $subItems[$j];
                        switch ($nSubItem['flag']) {
                            case 'update':
                                $cSubItem = BillSubItem::find($nSubItem['id']);
                                $cSubItem->cost = $nSubItem['cost'];
                                $cSubItem->name = $nSubItem['name'];
                                $cSubItem->quatity = $nSubItem['quatity'];
                                $cSubItem->total_cost = $nSubItem['total_cost'];
                                $cSubItem->save();
                                break;
                            case 'new':
                                BillSubItem::create([
                                    'bill_item_id' => $nItem['id'],
                                    'product_item_id' => $nSubItem['product_item_id'],
                                    'name' => $nSubItem['name'],
                                    'cost' => $nSubItem['cost'],
                                    'quatity' => $nSubItem['quatity'],
                                    'total_cost' => $nSubItem['total_cost']
                                ]);
                                break;
                            case 'delete':
                                $subBillitem = BillSubItem::find($nSubItem['id']);
                                if ($subBillitem != null) {
                                    $subBillitem->delete();
                                }
                                break;
                        }
                    }
                    break;
                case 'new':
                    $nItemId = BillItem::create(
                        ['bill_id' => $currentBill->id,
                         'product_id' => $nItem['product_id'],
                         'name' => $nItem['name'],
                         'cost' => $nItem['cost'],
                         'quatity' => $nItem['quatity'],
                         'ship_cost' => $nItem['ship_cost'],
                         'total_cost' => $nItem['total_cost']
                        ]
                    )->id;
                    for ($j = 0; $j < count($subItems) ; $j++) {
                        $nSubItem = $subItems[$j];
                        BillSubItem::create([
                            'bill_item_id' => $nItemId,
                            'product_item_id' => $nSubItem['product_item_id'],
                            'name' => $nSubItem['name'],
                            'cost' => $nSubItem['cost'],
                            'quatity' => $nSubItem['quatity'],
                            'total_cost' => $nSubItem['total_cost']
                        ]);
                    }
                    break;
                case 'nothing':
                    //skip
                    break;
                case 'delete':
                    $billSubItems = BillSubItem::where('bill_item_id', $nItem['id']);
                    if ($billSubItems != null) {
                        $billSubItems->delete();
                    }
                    $billItem = BillItem::find($nItem['id']);
                    if ($billItem != null) {
                        $billItem->delete();
                    }
                    break;
                default:
                break;
            }
        }
        $currentBill->total_cost = $request->total_cost;
        $currentBill->name = $request->name;
        $currentStatus = $currentBill->status;
        $currentBill->status = $request->status;
        $currentBill->save();
        $newBill = Bill::with(['items.sub_items'])->find($currentBill->id);
        $products = [];
        if ($currentStatus != 4 &&  $request->status == 3) {
            $items = $newBill->items;
            for ($i = 0; $i < count($items) ; $i++) {
                $mItem = $items[$i];
                $product = Product::find($mItem['product_id']);
                if (count($mItem['sub_items']) == 0) {
                    $product->quatity = $product['quatity'] + $mItem['quatity'];
                    $product->save();
                } else {
                    $product['items'] = [];
                    $subs = $mItem['sub_items'];
                    for ($j = 0 ; $j < count($subs) ; $j++) {
                        $mSub = $subs[$j];
                        $productItem = ProductItem::find($mSub['product_item_id']);
                        $productItem->quatity = $productItem['quatity'] + $mSub['quatity'];
                        $productItem->save();
                        $product['items'] = $productItem;
                    }
                }
                $products[] = $product;
            }
        }
        return response()->json(['bill' => $newBill, 'products' => $products]);
    }

    public function delete(Request $request) {
        $bill = Bill::find($request->id);
        if ($bill == null) {
            return response()->json(['error' => 'Can\'t delete this bill'], 400);
        }
        $bill->delete();
        return response()->json('Delete successfully');
    }

    public function compareBillItem($a, $b) {
        if ($a['name'] !== $b['name']
        || $a['cost'] !== $b['cost']
        || $a['product_id'] !== $b['product_id']
        || $a['quatity'] !== $b['quatity']
        || $a['ship_cost'] !== $b['ship_cost']
        || $a['total_cost'] !== $b['total_cost']) {
            return false;
        }
        return true;
    }

    public function compareSubItem($a, $b) {
        if ($a['cost'] !== $b['cost']
        || $a['name'] !== $b['name']
        || $a['product_item_id'] !== $b['product_item_id']
        || $a['quatity'] !== $b['quatity']
        || $a['total_cost'] !== $b['total_cost']) {
            return false;
        }
        return true;
    }
}
