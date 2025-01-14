<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MstrSloc;

class SlocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $search = $request->get('search', '');
        $slocs = MstrSloc::where('Tp_mtCode', 'like', '%' . $search . '%')
            ->paginate(10);

        return view('admin.master.sloc.index', compact('slocs', 'search'));
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

        $request->validate([
            'Tp_mtCode' => 'required|array',
            'Tp_mtCode.*' => 'required|integer|digits_between:1,11',
            'Tp_name' => 'required|array',
            'Tp_name.*' => 'required|string|max:255',
        ]);




        foreach ($request->Tp_mtCode as $index => $code) {
            MstrSloc::create([
                'Tp_mtCode' => $code,
                'Tp_name' => $request->Tp_name[$index],
            ]);
        }

        return redirect()->route('Sloc.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'Tp_mtCode' => 'required|integer',
            'Tp_name' => 'required|string|max:255',
        ]);

        $sloc = MstrSloc::findOrFail($id);
        $sloc->Tp_mtCode = $request->Tp_mtCode;
        $sloc->Tp_name = $request->Tp_name;
        $sloc->save();

        return redirect()->route('Sloc.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $sloc = MstrSloc::findOrFail($id);
        $sloc->delete();
        return redirect()->route('Sloc.index');
    }
}