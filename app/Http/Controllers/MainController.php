<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceProductHistory;
use App\Models\History;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Pest\Laravel\get;

class MainController extends Controller
{
    public function index() {
        $data = Type::with('devices')->with('devices.type')->with('devices.deviceProductHistoryActive.product')->with('devices.activeHistories')->get();
        if (Auth::user()->role === 'admin') {
            return view('main.index', compact('data'));
        } elseif (Auth::user()->role === 'cashier') {
            return view('cashier.index', compact('data'));
        }
        return redirect()->route('dashboard');
    }


    public function dashboard()
    {
        if (Auth::user()->role === 'admin') {
            // === Kirimlar (Foydalanuvchi va Mahsulot) ===
            $userProfitTotal = History::sum('paid_price');
            $userProfitMonthly = History::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('paid_price');
            $userProfitToday = History::whereDate('created_at', now())->sum('paid_price');

            $productProfitTotal = DeviceProductHistory::sum(DB::raw('sold * count'));
            $productProfitMonthly = DeviceProductHistory::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum(DB::raw('sold * count'));
            $productProfitToday = DeviceProductHistory::whereDate('created_at', now())
                ->sum(DB::raw('sold * count'));

            $totalIncome = $userProfitTotal + $productProfitTotal;
            $monthlyIncome = $userProfitMonthly + $productProfitMonthly;
            $todayIncome = $userProfitToday + $productProfitToday;

            // === Chiqimlar (Mahsulotlar kirimidan) ===
            $totalExpense = ProductHistory::sum(DB::raw('income * count'));
            $monthlyExpense = ProductHistory::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum(DB::raw('income * count'));
            $todayExpense = ProductHistory::whereDate('created_at', now())
                ->sum(DB::raw('income * count'));

            // === Foyda (Kirim - Chiqim) ===
            $totalProfit = $totalIncome - $totalExpense;
            $monthlyProfit = $monthlyIncome - $monthlyExpense;
            $todayProfit = $todayIncome - $todayExpense;

            // === Kompyuter soni ===
            $computerCount = Device::count();

            // === Grafik uchun 12 oylik ma’lumotlar ===
            $monthlyIncomeData = [];
            $monthlyExpenseData = [];
            $monthlyProfitData = [];
            $months = [];

            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $months[] = $date->format('M Y');

                $userSum = History::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('paid_price');

                $productSum = DeviceProductHistory::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum(DB::raw('sold * count'));

                $income = $userSum + $productSum;

                $expense = ProductHistory::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum(DB::raw('income * count'));

                $profit = $income - $expense;

                $monthlyIncomeData[] = round($income);
                $monthlyExpenseData[] = round($expense);
                $monthlyProfitData[] = round($profit);
            }

            return view('main.dashboard', compact(
                'totalIncome', 'monthlyIncome', 'todayIncome',
                'totalExpense', 'monthlyExpense', 'todayExpense',
                'totalProfit', 'monthlyProfit', 'todayProfit',
                'computerCount',
                'monthlyIncomeData', 'monthlyExpenseData', 'monthlyProfitData',
                'months'
            ));
        }

        if (Auth::user()->role === 'cashier') {
            return view('cashier.dashboard');
        }

        return redirect()->route('dashboard');
    }

    public function calculate(Request $request)
    {
        // Validate input
        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // === Kirimlar (Foydalanuvchi va Mahsulot) ===
        $userProfitTotal = History::whereBetween('created_at', [$startDate, $endDate])->sum('paid_price');
        $productProfitTotal = DeviceProductHistory::whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('sold * count'));

        $totalIncome = $userProfitTotal + $productProfitTotal;

        // === Chiqimlar (Mahsulotlar kirimidan) ===
        $totalExpense = ProductHistory::whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('income * count'));

        // === Foyda (Kirim - Chiqim) ===
        $totalProfit = $totalIncome - $totalExpense;

        // Return the results as JSON
        return response()->json([
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'totalProfit' => $totalProfit,
        ], 200);
    }





    public function types(Request $request) {
        if (Auth::user()->role === 'admin') {
            $query = Type::with('devices');

            if ($request->has('search') && $request->search) {
                $query->where(function($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('price', 'like', '%' . $request->search . '%');
                });
            }

            $data = $query->paginate(100); // Pagination: har sahifada 50 ta yozuv
            return view('main.types', compact('data'));
        }
        return redirect()->route('dashboard');
    }

    public function devices(Request $request) {
        if (Auth::user()->role === 'admin') {
            $query = Device::with('type');

            // Agar qidiruv parametri kiritilgan bo'lsa
            if ($request->has('search') && $request->search) {
                $query->where('name', 'like', '%' . $request->search . '%'); // 'name' ustunini qidirish
            }

            $data = $query->paginate(100); // Pagination: har sahifada 50 ta yozuv
            $types = Type::all();
            return view('main.devices', compact('data', 'types'));
        }
        return redirect()->route('dashboard');
    }

    public function history(Request $request)
    {
        $query = History::with(['productHistory.product'])
        ->when(true, function ($query) {
            return $query->where('paid_price', '!=', 0);
        });

        // Agar qidiruv parametri kiritilgan bo'lsa
        if ($request->has('search') && $request->search) {
            $query->where(function ($query) use ($request) {
                $query->whereHas('device', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                })
                    ->orWhereHas('productHistory', function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->search . '%'); // yoki boshqa ustun nomi
                    })
                    ->orWhere('started_at', 'like', '%' . $request->search . '%')
                    ->orWhere('paid_price', 'like', '%' . $request->search . '%')
                    ->orWhere('finished_at', 'like', '%' . $request->search . '%');
            });
        }
        $products = DeviceProductHistory::with('device');

        // DESC bo'yicha paid_price bo'yicha saralash
        $data = $query->orderBy('started_at', 'desc')->paginate(100);

        if (Auth::user()->role === 'admin') {
            return view('main.history', compact('data'));
        } elseif (Auth::user()->role === 'cashier') {
            return view('cashier.history', compact('data'));
        }

        return redirect()->route('dashboard');
    }


    public function products(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            $query = Product::query();

            // Agar qidiruv parametri kiritilgan bo'lsa
            if ($request->has('search') && $request->search) {
                $query->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('count', 'like', '%' . $request->search . '%')
                        ->orWhere('income', 'like', '%' . $request->search . '%')
                        ->orWhere('expense', 'like', '%' . $request->search . '%');
                });
            }

            $data = $query->paginate(100); // Pagination: har sahifada 50 ta yozuv
            return view('main.products', compact('data'));
        }
        return redirect()->route('dashboard');
    }
    public function product_history(Request $request)
    {
        // Query for ProductHistory data
        $productQuery = ProductHistory::query();

        if ($request->has('search') && $request->search) {
            $productQuery->where(function ($query) use ($request) {
                $query->whereHas('product', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                })
                    ->orWhere('count', 'like', '%' . $request->search . '%')
                    ->orWhere('income', 'like', '%' . $request->search . '%')
                    ->orWhere('expense', 'like', '%' . $request->search . '%')
                    ->orWhere('created_at', 'like', '%' . $request->search . '%');
            });
        }

        // Get the ProductHistory data
        $productHistory = $productQuery->select('product_id', 'count', 'income', 'expense', 'created_at')
            ->with('product') // Eager load the product relationship
            ->get(); // Fetch all records

        // Query for DeviceProductHistory data
        $deviceQuery = DeviceProductHistory::query();

        if ($request->has('search') && $request->search) {
            $deviceQuery->where(function ($query) use ($request) {
                $query->whereHas('device', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                })
                    ->orWhereHas('product', function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // Debug device query (without filter)
        $deviceHistory = $deviceQuery->select('device_id', 'product_id', 'count', 'sold', 'status', 'created_at')
            ->with(['device', 'product']) // Eager load device and product
            ->get(); // Fetch all records

        $productHistory = $productHistory->map(function($history) {
            return [
                'type' => 'product',
                'product_id' => $history->product_id,
                'count' => $history->count,
                'income' => $history->income,
                'expense' => $history->expense,
                'created_at' => "$history->created_at"
            ];
        });

        $deviceHistory = $deviceHistory->map(function($history) {
            return [
                'type' => 'device',
                'product_id' => $history->product_id,
                'count' => $history->count,
                'sold' => $history->sold,
                'status' => $history->status,
                'created_at' => "$history->created_at"
            ];
        });

        $mergedHistory = $productHistory->merge($deviceHistory);

        $mergedHistory = $mergedHistory->map(function ($item) {
            // Convert array back to model if it's not an instance of the model
            if (!($item instanceof ProductHistory) && !isset($item['sold'])) {
                return new ProductHistory($item);
            }

            if (!($item instanceof DeviceProductHistory) && isset($item['sold'])) {
                return new DeviceProductHistory($item);
            }

            return $item;
        });

        // Sort the merged data by 'created_at'
        $mergedHistory = $mergedHistory->sortByDesc('created_at');


        // Pagination
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 50;

        $currentPageItems = $mergedHistory->slice(($currentPage - 1) * $perPage, $perPage);

        // Create the paginator
        $data = new LengthAwarePaginator(
            $currentPageItems,
            $mergedHistory->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );


        // Return the view with the data
        if (Auth::user()->role === 'admin') {
            return view('main.product-history', compact('data'));
        } elseif (Auth::user()->role === 'cashier') {
            return view('cashier.product-history', compact('data'));
        }

        return redirect()->route('dashboard');
    }

}
