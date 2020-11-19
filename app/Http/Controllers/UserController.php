<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::with(['roles:id,name,slug','roles.permissions:id,name,slug'])->get());
    }

    public function add(Request $request)
    {

    }

    public function updateRole(Request $request)
    {

    }

    public function block(Request $request)
    {

    }
}
