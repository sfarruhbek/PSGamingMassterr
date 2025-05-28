<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
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
            'price11' => 'required|numeric',
            'price21' => 'required|numeric',
        ]);

        $type = Type::create($validated);

        return response()->json(['success' => true, 'type' => $type]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Type $type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type $type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price11' => 'required|numeric',
            'price21' => 'required|numeric',
        ]);

        $type = Type::findOrFail($id);
        $type->update($validated);

        return response()->json(['success' => true, 'type' => $type]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $type = Type::find($id);

        if (!$type) {
            return response()->json(['error' => 'Topilmadi'], 404);
        }

        $type->delete();

        return response()->json(['success' => true]);
    }
}
