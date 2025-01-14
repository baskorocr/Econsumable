<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::paginate(20);
        return view('admin.master.role.index', compact('roles'));
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
            'NameRole' => 'required|array',
            'NameRole.*' => 'required|string|max:255',
        ]);

        foreach ($request->NameRole as $name) {
            Role::create(['NameRole' => $name]);
        }
        return redirect()->route('Role.index');
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

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'NameRole' => 'required|string|max:255',
        ]);



        $role = Role::findOrFail($id);
        $role->NameRole = $request->NameRole;
        $role->save();
        return redirect()->route('Role.index');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $role = Role::findOrFail($id);

        $role->delete();
        return redirect()->route('Role.index');
    }
}