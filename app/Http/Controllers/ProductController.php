<?php

namespace App\Http\Controllers;

use App\Models\DeviceProductHistory;
use App\Models\History;
use App\Models\Product;
use App\Models\ProductHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'count' => 'required|integer|min:1',
            'income' => 'required|numeric|min:0',
            'expense' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        $product = Product::create($request->all());

        ProductHistory::create([
            'product_id' => $product->id,
            'count' => $product->count,
            'income' => $product->income,
            'expense' => $product->expense,
        ]);

        return redirect()->back()->with(['store'=>'success']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'expense' => 'numeric|min:0',
        ]);

        $product = Product::findOrFail($id);

        $product->name = $request->name;
        if ($request->has('expense')) {
            $product->expense = $request->expense;
        }
        $product->save();

        return redirect()->back()->with(['update'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Mahsulot topilmadi.'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Mahsulot muvaffaqiyatli o‘chirildi.']);
    }

    public function add_product(Request $request){
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'count' => 'required|integer|min:1',
            'income' => 'numeric|min:0',
            'expense' => 'numeric|min:0',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->count += $request->count;
        if ($request->has('income')) {
            $product->income = $request->income;
        }
        if ($request->has('expense')) {
            $product->expense = $request->expense;
        }
        $product->save();

        ProductHistory::create([
            'product_id' => $product->id,
            'count' => $request->count,
            'income' => $request->has('income') ? $request->income : $product->income,
            'expense' => $request->has('expense') ? $request->expense : $product->expense,
        ]);

        return redirect()->back()->with(['add_product'=>'success']);
    }

    public function update_product(Request $request)
    {
        $hs = ProductHistory::findOrFail($request->id);
        $hs->update([
            'count'=>$request->count,
        ]);
        return redirect()->back()->with(['add_product'=>'success']);
    }

    public function sell_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.count' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Tekshiruvdan o‘tmadi', 'errors' => $validator->errors()], 422);
        }

        $deviceId = $request->device_id;
        $products = $request->products;
        $history = History::where('finished_at', null)->where('device_id', $deviceId)->first();

        foreach ($products as $item) {
            $product = Product::find($item['product_id']);

            if ($product->count < $item['count'] && $item['product_history_id'] == 0) {
                return response()->json([
                    'message' => "Mahsulot yetarli emas: {$product->name} (mavjud: {$product->count}, so‘ralgan: {$item['count']})"
                ], 400);
            }

            if($item['product_history_id'] !== 0){
                $pr = DeviceProductHistory::findOrFail($item['product_history_id']);
                $product->count -= $item['count'] - $pr->count;
            }else{
                $product->count -= $item['count'];
            }
            $product->save();

            if($deviceId === null){
                DeviceProductHistory::create([
                    'device_id' => null,
                    'product_id' => $product->id,
                    'count' => $item['count'],
                    'sold' => $product->expense * $item['count'],
                    'status' => true
                ]);
            } else{


                if($item['product_history_id'] !== 0){
                    $pr = DeviceProductHistory::findOrFail($item['product_history_id']);
                    $pr->update([
                        'count' => $item['count'],
                        'sold' => $product->expense * $item['count'],
                    ]);
                } else {
                    DeviceProductHistory::create([
                        'device_id' => $deviceId,
                        'history_id' => $history->id,
                        'product_id' => $product->id,
                        'count' => $item['count'],
                        'sold' => $product->expense * $item['count'],
                        'status' => false
                    ]);
                }
            }
        }


        return response()->json(['message' => 'Mahsulotlar muvaffaqiyatli sotildi']);
    }
}
