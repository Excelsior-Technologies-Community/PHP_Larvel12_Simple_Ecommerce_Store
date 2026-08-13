<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $customerId = auth('customer')->id();
        $addresses = Address::where('customer_id', $customerId)
            ->latest()
            ->get();

        return view('address.index', compact('addresses'));
    }

    public function store(Request $request)
    {
        $customerId = auth('customer')->id();

        $request->validate([
            'full_name' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required|min:6',
        ]);

        Address::create([
            'customer_id' => $customerId,
            'full_name' => $request->full_name,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'nearby' => $request->nearby,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'type' => $request->type ?? 'shipping',
            'label' => $request->label,
            'is_default' => $request->is_default ?? false,
        ]);

        if ($request->has('continue_to_checkout')) {
            return redirect()->route('addresses.index')
                ->with('success', 'Address saved. Click Place Order to checkout.');
        }

        return redirect()->route('addresses.index')->with('success', 'Address saved successfully');
    }

    public function edit(Address $address)
    {
        $customerId = auth('customer')->id();

        if ($address->customer_id !== $customerId) {
            abort(403);
        }

        return view('address.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        $customerId = auth('customer')->id();

        if ($address->customer_id !== $customerId) {
            abort(403);
        }

        $request->validate([
            'full_name' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required|min:6',
        ]);

        $address->update([
            'full_name' => $request->full_name,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'nearby' => $request->nearby,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'type' => $request->type ?? 'shipping',
            'label' => $request->label,
            'is_default' => $request->is_default ?? false,
        ]);

        return redirect()->route('addresses.index')->with('success', 'Address updated successfully');
    }

    public function destroy(Address $address)
    {
        $customerId = auth('customer')->id();

        if ($address->customer_id !== $customerId) {
            abort(403);
        }

        $address->delete();

        return back()->with('success', 'Address deleted successfully');
    }

    public function setDefault(Address $address)
    {
        $customerId = auth('customer')->id();

        if ($address->customer_id !== $customerId) {
            abort(403);
        }

        Address::where('customer_id', $customerId)->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Default address updated');
    }

    public function saveForCheckout(Request $request)
    {
        $customerId = auth('customer')->id();

        if ($request->isMethod('GET')) {
            return redirect()->route('addresses.index')
                ->with('info', 'Select an address and click Place Order to checkout.');
        }

        if ($request->isMethod('POST') && !empty($request->address_id)) {
            $address = Address::where('id', $request->address_id)
                ->where('customer_id', $customerId)
                ->firstOrFail();

            return redirect()->route('addresses.index')
                ->with('success', 'Address selected. Click Place Order to checkout.');
        }

        $request->validate([
            'full_name' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required|min:6',
        ]);

        $address = Address::firstOrCreate(
            [
                'customer_id' => $customerId,
                'address' => $request->address,
                'nearby' => $request->nearby,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
            ],
            [
                'full_name' => $request->full_name,
                'mobile' => $request->mobile,
                'type' => $request->type ?? 'shipping',
                'label' => $request->label,
                'is_default' => $request->is_default ?? false,
            ]
        );

        return redirect()->route('addresses.index')
            ->with('success', 'Address saved successfully');
    }
}
