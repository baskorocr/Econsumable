<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MstrLine;

class AdminController extends Controller
{
    public function index()
    {

        return view('admin.index');
    }



}