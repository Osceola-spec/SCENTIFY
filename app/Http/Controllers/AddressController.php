<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'nullable|string|max:100',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);

        $data = $request->only(['label','first_name','last_name','phone','address','city','postal_code']);
        $data['user_id'] = auth()->id();
        $data['is_default'] = $request->has('is_default') ? true : false;

        if ($data['is_default']) {
            // unset previous default
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        Address::create($data);

        return redirect()->back()->with('success', 'Alamat berhasil disimpan.');
    }

    public function destroy(Address $address)
    {
        $this->authorize('delete', $address);
        $address->delete();
        return redirect()->back()->with('success', 'Alamat dihapus.');
    }

    public function update(Request $request, Address $address)
    {
        $this->authorize('update', $address);

        $request->validate([
            'label' => 'nullable|string|max:100',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);

        $data = $request->only(['label','first_name','last_name','phone','address','city','postal_code']);
        $data['is_default'] = $request->has('is_default') ? true : false;

        if ($data['is_default']) {
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        $address->update($data);

        return redirect()->back()->with('success', 'Alamat diperbarui.');
    }
}
