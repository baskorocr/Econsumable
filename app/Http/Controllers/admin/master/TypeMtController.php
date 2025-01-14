<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use App\Models\MstrTypeMaterial;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Foreach_;

class TypeMtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $types = MstrTypeMaterial::all();
    //     return view('admin.master.type.index', compact('types'));
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'Ty_desc' => 'required|array',
    //         'Ty_desc.* ' => 'required|string|max:255',
    //     ]);


    //     foreach ($request->Ty_desc as $type) {
    //         MstrTypeMaterial::create([

    //             'Ty_desc' => $type
    //         ]);
    //     }

    //     return redirect()->route('Type.index');

    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(string $id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {

    //     $request->validate([
    //         'Ty_desc' => 'required|string',
    //         'Ty_desc.* ' => 'required|string|max:255',
    //     ]);



    //     $ty = MstrTypeMaterial::findOrFail($id);

    //     $ty->Ty_desc = $request->Ty_desc;
    //     $ty->save();

    //     return redirect()->route('Type.index');

    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(string $id)
    // {
    //     $ty = MstrTypeMaterial::findOrFail($id);
    //     $ty->delete();
    //     return redirect()->route('Type.index');
    // }
}