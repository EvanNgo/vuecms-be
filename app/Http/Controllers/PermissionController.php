<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    private $permission;
    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }
    public function index()
    {
        return response()->json(Permission::all());
    }

    public function get(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required'
        ]);
        return Permission::where('id', $validatedData)->first();
    }
}
