<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceProductHistory;
use App\Models\History;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index() {
        if (Auth::user()->role === 'admin') {
            $data = Type::with('devices')->with('devices.type')->with('devices.deviceProductHistoryActive.product')->with('devices.activeHistories')->get();
            return view('main.index', compact('data'));
        } elseif (Auth::user()->role === 'cashier') {
            $data = Type::with('devices')->with('devices.type')->with('devices.activeHistories')->with('devices.deviceProductHistoryActive.product')->get();
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





    public function types() {
        if (Auth::user()->role === 'admin') {
            $data = Type::with('devices')->get();
            return view('main.types', compact('data'));
        }
        return redirect()->route('dashboard');
    }

    public function devices() {
        if (Auth::user()->role === 'admin') {
            $data = Device::with('type')->get();
            $types = Type::all();
            return view('main.devices', compact('data','types'));
        }
        return redirect()->route('dashboard');
    }

    public function history() {
        if (Auth::user()->role === 'admin') {
            $data = History::when('paid_price' != 0)->latest()->get();
            return view('main.history', compact('data'));
        } elseif (Auth::user()->role === 'cashier') {
            $data = History::latest()->get();
            return view('cashier.history', compact('data'));
        }
        return redirect()->route('dashboard');
    }
    public function products()
    {
        if (Auth::user()->role === 'admin') {
            $data = Product::all();
            return view('main.products', compact('data'));
        }
        return redirect()->route('dashboard');
    }
}
