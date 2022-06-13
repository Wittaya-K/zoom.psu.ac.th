<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class MenuController extends Controller
{
    public function index()
    {

        $users = User::all();
        
        return view('partials.sidebar', compact('users'));
    }
}
