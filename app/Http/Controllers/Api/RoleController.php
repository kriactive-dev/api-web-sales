<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
   public function index()
    {
        return Role::with('permissions')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|unique:roles,name']);
        $role = Role::create($data);
        return response()->json($role, 201);
    }

    public function show(Role $role)
    {
        return $role->load('permissions');
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate(['name' => 'required|unique:roles,name,' . $role->id]);
        $role->update($data);
        return response()->json($role);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['message' => 'Role removida']);
    }
}
