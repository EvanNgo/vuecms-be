<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json(Role::with('permissions')->get());
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'permission_id' => 'required'
        ]);
        $permission = Permission::where('id', $request->permission_id)->first();
        $role = Role::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);
        $role->setPermission($permission);
        return response()->json($role);
    }

    public function update(Request $request)
    {
        
    }

    public function delete(Request $request)
    {
        
    }
}
