<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $user = new User();
        if (request()->ajax()) {
            return view('users.partials.form', compact('user'))->render();
        }

        return redirect()->route('users.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'profile_photo_path' => 'nullable|file|image|max:5120',
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('profile_photo_path')) {
            $path = $request->file('profile_photo_path')->store('profile_photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        $user = User::create($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'id' => $user->id]);
        }

        return redirect()->route('users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        if (request()->ajax()) {
            return view('users.partials.form', compact('user'))->render();
        }

        return redirect()->route('users.index');
    }

    public function show(Request $request, User $user)
    {
        if ($request->ajax()) {
            return view('users.partials.modal-content', compact('user'))->render();
        }
        return redirect()->route('users.index');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'profile_photo_path' => 'nullable|file|image|max:5120',
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('profile_photo_path')) {
            $path = $request->file('profile_photo_path')->store('profile_photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        $user->update($data);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted.');
    }
}
