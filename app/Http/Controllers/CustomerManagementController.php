<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $customers = Customer::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->withCount('orders')
            ->latest()
            ->paginate(10);

        return view('admin.customers.index', compact('customers', 'search'));
    }

    public function show(Customer $customer)
    {
        $customer->load('orders', 'addresses');
        return view('admin.customers.show', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
        ]);

        $customer->update($request->only('name', 'email'));

        return back()->with('success', 'Customer updated successfully');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Customer deleted successfully');
    }
}
