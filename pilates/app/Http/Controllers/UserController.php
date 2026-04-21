<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Services\AuditLogService;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (($request->user()->role ?? '') !== 'admin') {
                abort(403);
            }
            return $next($request);
        });
    }
    public function index()
    {
        $q = request()->query('q');
        $role = request()->query('role');

        $query = User::query();
        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($role && in_array($role, ['admin','petugas','user'])) {
            $query->where('role', $role);
        }

        $users = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('users.index', compact('users', 'q', 'role'));
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
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,petugas,user',
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

        // audit log
        try { AuditLogService::log(AuditLogService::ACTION_CREATE, [
            'subject_type' => 'user',
            'subject_id' => $user->id,
            'message' => 'Created user',
            'meta' => ['email' => $user->email]
        ]); } catch (\Throwable $e) {}

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
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,petugas,user',
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
            // store new photo
            $path = $request->file('profile_photo_path')->store('profile_photos', 'public');
            $data['profile_photo_path'] = $path;
            // remove old photo from storage if exists
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                try {
                    Storage::disk('public')->delete($user->profile_photo_path);
                } catch (\Throwable $e) {
                    // ignore deletion errors
                }
            }
        }

        $user->update($data);

        // audit log
        try { AuditLogService::log(AuditLogService::ACTION_UPDATE, [
            'subject_type' => 'user',
            'subject_id' => $user->id,
            'message' => 'Updated user',
            'meta' => ['email' => $user->email]
        ]); } catch (\Throwable $e) {}

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        // delete profile photo from storage if exists
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            try {
                Storage::disk('public')->delete($user->profile_photo_path);
            } catch (\Throwable $e) {
                // ignore
            }
        }

        // capture deleted user's id/email for log
        try { AuditLogService::log(AuditLogService::ACTION_DELETE, [
            'subject_type' => 'user',
            'subject_id' => $user->id,
            'message' => 'Deleted user',
            'meta' => ['email' => $user->email]
        ]); } catch (\Throwable $e) {}

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted.');
    }
}
