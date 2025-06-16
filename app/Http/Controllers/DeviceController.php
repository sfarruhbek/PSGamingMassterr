<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceProductHistory;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type_id' => 'required|exists:types,id',
        ]);

        $device = Device::create($validated);

        return response()->json([
            'success' => true,
            'device' => $device
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type_id' => 'required|exists:types,id',
        ]);

        $device->update($validated);

        return response()->json([
            'success' => true,
            'device' => $device
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        $device->delete();

        return response()->json([
            'success' => true,
            'message' => 'Qurilma o‘chirildi'
        ]);
    }

    public function start($id, Request $request)
    {
        $request->validate([
            'user_count' => 'required|integer|min:1|max:50',
        ]);

        $now = Carbon::now();
        $count = $request->input('user_count');
        for ($i = 0; $i < $count; $i++) {
            History::create([
                'device_id' => $id,
                'started_at' => $now,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Qurilma boshlandi ($count foydalanuvchi)",
            'device' => Device::with('activeHistories')->with('type')->find($id),
        ]);
    }

    public function finishSingleUser(Request $request, $deviceId, $historyId)
    {
        return response()->json("Not Found",404);
        $history = History::where('device_id', $deviceId)
            ->where('id', $historyId)
            ->whereNull('finished_at')
            ->firstOrFail();

        $history->finished_at = now();

        // Use paid_price from request if available, otherwise calculate
        if ($request->has('paid_price')) {
            $history->paid_price = intval($request->input('paid_price'));
        } else {
            $minutes = now()->diffInMinutes($history->started_at);
            $pricePerHour = $history->price ?? 0;
            $history->paid_price = intval($minutes * ($pricePerHour / 60));
        }

        $history->save();

        return response()->json(['success' => true, 'message' => 'User finished']);
    }

    public function finishAllUsers(Request $request, $deviceId)
    {
        $histories = History::where('device_id', $deviceId)
            ->whereNull('finished_at')
            ->get();

        $paidPrices = $request->input('paid_prices', 0);
        $products = $request->input('products', []);



        // ✅ Mahsulot tarixini yangilash
        $products_cost = DeviceProductHistory::where('device_id', $deviceId)->where('status', false)->sum('sold');
        foreach ($products as $product) {
            DeviceProductHistory::where('device_id', $deviceId)
                ->where('product_id', $product['product_id'])
                ->where('status', false)
                ->update(['status' => true]);
        }

        $ss = true;
        foreach ($histories as $idx => $history) {
            $history->finished_at = now();
            if($ss) {
                if (isset($paidPrices)) {
                    $history->paid_price = intval($paidPrices - $products_cost);
                }
//                else {
//                    $minutes = now()->diffInMinutes($history->started_at);
//                    $pricePerHour = $history->price ?? 0;
//
//                    if($pricePerHour == 0){
//                        $history->paid_price = 0;
//                    } else {
//                        $history->paid_price = intval($minutes * ($pricePerHour / 60));
//                    }
//                }
            }else{
                $history->paid_price = 0;
            }
            $history->save();
            $ss = false;
        }

        return response()->json([
            'success' => true,
            'message' => 'Barcha foydalanuvchilar va mahsulotlar yakunlandi'
        ]);
    }

}
