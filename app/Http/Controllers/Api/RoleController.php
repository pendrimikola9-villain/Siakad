<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Http\Resources\Api\RoleResource;
use App\Http\Requests\Api\RoleRequest;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public function index()
    {
        return RoleResource::collection(Role::with(['parent', 'children'])->paginate(10));
    }

    public function store(RoleRequest $request): RoleResource
    {
        $role = Role::create($request->validated());
        return new RoleResource($role->load(['parent', 'children']));
    }

    public function show(Role $role): RoleResource
    {
        return new RoleResource($role->load(['parent', 'children']));
    }

    public function update(RoleRequest $request, Role $role): RoleResource
    {
        $role->update($request->validated());
        return new RoleResource($role->load(['parent', 'children']));
    }

    public function destroy(Role $role): JsonResponse
    {
        $role->delete();
        return response()->json(['message' => 'Role berhasil dihapus']);
    }
}
