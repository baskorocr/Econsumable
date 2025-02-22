<?php

namespace App\Http\Controllers\admin\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use Exception;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::with('role')->get();
        return view('admin.master.user.index', compact('user')); // Make sure this view exists
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        try {
            $user = User::where('npk', $id)->first();

            // Hapus user dari database
            $user->delete();
            Alert::success('Delete User Success', 'User has been deleted');

        } catch (Exception $e) {
            Alert::success('Delete User failed', 'Check Data before or contact development for check that');
        }


        // Redirect kembali ke halaman daftar user dengan pesan sukses
        return redirect()->back();
    }
}