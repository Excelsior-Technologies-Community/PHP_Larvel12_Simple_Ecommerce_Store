<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::with('user')->latest()->paginate(10);
        return view('admin.staff.index', compact('staff'));
    }

    public function show(Staff $staff)
    {
        $staff->load('user');
        return view('admin.staff.show', compact('staff'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'designation' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'is_active' => $request->has('is_active'),
        ]);

        Staff::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'address' => $request->address,
            'designation' => $request->designation,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff created successfully');
    }

    public function edit(Staff $staff)
    {
        $roles = Role::all();
        return view('admin.staff.edit', compact('staff', 'roles'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->user_id,
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'designation' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $staff->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:6|confirmed',
            ]);
            $staff->user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $staff->update([
            'phone' => $request->phone,
            'address' => $request->address,
            'designation' => $request->designation,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully');
    }

    public function destroy(Staff $staff)
    {
        $user = $staff->user;
        $staff->delete();
        $user->delete();

        return back()->with('success', 'Staff deleted successfully');
    }
}
