<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index() {
        $user = auth()->user();
        if ($user->role === 'judge') {
            $users = User::where('role', 'participant')->get();
        } else {
            $users = User::all();
        }
        return view('admin.users', compact('users'));
    }

    public function create() {
        return view('admin.users.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,judge,participant',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user) {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user) {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,judge,participant',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        $authUser = auth()->user();
        $route = $authUser->role === 'judge' ? 'judge.users.index' : 'admin.users.index';

        return redirect()->route($route)->with('success', 'User updated successfully.');
    }

    public function destroy(User $user) {
        $user->delete();

        $authUser = auth()->user();
        $route = $authUser->role === 'judge' ? 'judge.users.index' : 'admin.users.index';

        return redirect()->route($route)->with('success', 'User deleted successfully.');
    }
}
