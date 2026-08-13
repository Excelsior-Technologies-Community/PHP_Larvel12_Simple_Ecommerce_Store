<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    private $availablePermissions = [
        'dashboard' => 'Dashboard',
        'analytics' => 'Analytics',
        'products' => 'Products',
        'variants' => 'Product Variants',
        'stock' => 'Stock Management',
        'categories' => 'Categories',
        'colors' => 'Colors',
        'sizes' => 'Sizes',
        'orders' => 'Orders',
        'customers' => 'Customers',
        'staff' => 'Staff Management',
        'roles' => 'Roles & Permissions',
        'activity_logs' => 'Activity Logs',
        'discounts' => 'Discounts',
        'coupons' => 'Coupons',
        'banners' => 'Banners',
        'featured_products' => 'Featured Products',
        'cms_pages' => 'CMS Pages',
        'reviews' => 'Reviews',
        'cancellations' => 'Cancellations & Returns',
        'import_export' => 'Import / Export',
    ];

    public function index()
    {
        $roles = Role::latest()->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create', [
            'permissions' => $this->availablePermissions,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'permissions' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $permissions = [];
        if ($request->has('permissions')) {
            foreach ($this->availablePermissions as $key => $label) {
                $permissions[$key] = true;
            }
        }

        Role::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'permissions' => $permissions,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', [
            'role' => $role,
            'permissions' => $this->availablePermissions,
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'slug' => 'required|string|max:255|unique:roles,slug,' . $role->id,
            'permissions' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $permissions = [];
        if ($request->has('permissions')) {
            foreach ($this->availablePermissions as $key => $label) {
                $permissions[$key] = true;
            }
        }

        $role->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'permissions' => $permissions,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully');
    }

    public function show(Role $role)
    {
        return view('admin.roles.show', compact('role'));
    }

    public function destroy(Role $role)
    {
        if ($role->slug === 'super_admin') {
            return back()->with('error', 'Cannot delete Super Admin role');
        }

        $role->delete();
        return back()->with('success', 'Role deleted successfully');
    }
}
