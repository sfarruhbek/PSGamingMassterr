<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\History;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index() {
        if (Auth::user()->role === 'admin') {
            $data = Type::with('devices')->with('devices.type')->with('devices.activeHistories')->get();
            return view('main.index', compact('data'));
        } elseif (Auth::user()->role === 'cashier') {
            $data = Type::with('devices')->with('devices.type')->with('devices.activeHistories')->get();
            return view('cashier.index', compact('data'));
        }
        return redirect()->route('dashboard');
    }

    public function dashboard() {
        if (Auth::user()->role === 'admin') {
            return view('main.dashboard');
        } elseif (Auth::user()->role === 'cashier') {
            return view('cashier.dashboard');
        }
        return redirect()->route('dashboard');
    }

    public function types() {
        if (Auth::user()->role === 'admin') {
            $data = Type::all();
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
            $data = History::latest()->get();
            return view('main.history', compact('data'));
        } elseif (Auth::user()->role === 'cashier') {
            $data = History::latest()->get();
            return view('cashier.history', compact('data'));
        }
        return redirect()->route('dashboard');
    }
}
