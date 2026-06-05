<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();
        $provinces = \App\Models\Province::orderBy('name')->get();
        return view('addresses.index', compact('addresses', 'provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'nullable|string|max:100',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province_id' => 'nullable|string|max:20',
            'city_id' => 'nullable|string|max:20',
            'postal_code' => 'required|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);

        $data = $request->only(['label','first_name','last_name','phone','address','city','province_id','city_id','postal_code']);
        $data['user_id'] = auth()->id();
        $data['is_default'] = $request->has('is_default') ? true : false;

        if ($data['is_default']) {
            // unset previous default
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        Address::create($data);

        if ($request->redirect_to === 'checkout') {
            return redirect()->route('checkout')->with('success', 'Alamat berhasil disimpan.');
        }

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil disimpan.');
    }

    public function destroy(Address $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);
        $address->delete();
        return redirect()->route('addresses.index')->with('success', 'Alamat dihapus.');
    }

    public function update(Request $request, Address $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);

        // Normalize: form uses edit_ prefix
        $input = [];
        foreach (['label','first_name','last_name','phone','address','city','province_id','city_id','postal_code','is_default'] as $field) {
            $input[$field] = $request->input('edit_'.$field, $request->input($field));
        }
        $request->merge($input);

        $request->validate([
            'label' => 'nullable|string|max:100',
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'phone' => 'required|string|max:30',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province_id' => 'nullable|string|max:20',
            'city_id' => 'nullable|string|max:20',
            'postal_code' => 'required|string|max:20',
        ]);

        $data = collect($input)->only(['label','first_name','last_name','phone','address','city','province_id','city_id','postal_code'])->toArray();
        $data['is_default'] = !empty($input['is_default']);

        if ($data['is_default']) {
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        $address->update($data);

        return redirect()->route('addresses.index')->with('success', 'Alamat diperbarui.');
    }

    public function setDefault(Address $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);
        Address::where('user_id', auth()->id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        return redirect()->route('addresses.index')->with('success', 'Alamat utama diperbarui.');
    }
}
