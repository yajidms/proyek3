<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function toggle(User $user)
    {
        $user->is_active = ! $user->is_active;
        $user->save();
        return back()->with('status', 'User status updated');
    }

    public function setRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', 'in:admin,student'],
        ]);
        $user->syncRoles([$data['role']]);
        return back()->with('status', 'Role updated');
    }
}
