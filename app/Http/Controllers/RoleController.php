<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $roles = Role::withCount('users')->get();

        if ($request->wantsJson()) {
            return response()->json($roles);
        }

        return view('roles.index', compact('roles'));
    }
}
