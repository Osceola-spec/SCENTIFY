<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->get();
        return view('admin.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,NULL,id,deleted_at,NULL',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $logoPath = null;

        if ($request->hasFile('logo_image')) {
            $logoName = time() . '_' . $request->file('logo_image')->getClientOriginalName();
            $request->file('logo_image')->move(public_path('brand_image'), $logoName);
            $logoPath = $logoName;
        }

        Brand::create([
            'name' => $request->name,
            'logo_url' => $logoPath,
        ]);

        return redirect()->back()->with('success', 'New brand added successfully!');
    }

    // ========================================================
    // 1. FUNGSI UPDATE (Proses Edit dari Modal)
    // ========================================================
    public function update(Request $request, Brand $brand)
    {
        // Validasi input nama (abaikan unique untuk id brand ini sendiri)
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id . ',id,deleted_at,NULL',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'name' => $request->name,
        ];

        // Jika user mengunggah file logo baru
        if ($request->hasFile('logo_image')) {
            // Hapus logo lama dari server jika sebelumnya ada
            if ($brand->logo_url && file_exists(public_path('brand_image/' . basename($brand->logo_url)))) {
                unlink(public_path('brand_image/' . basename($brand->logo_url)));
            } elseif ($brand->logo_url && Storage::disk('public')->exists($brand->logo_url)) {
                Storage::disk('public')->delete($brand->logo_url);
            }

            // Simpan logo baru ke folder public/brand_image
            $logoName = time() . '_' . $request->file('logo_image')->getClientOriginalName();
            $request->file('logo_image')->move(public_path('brand_image'), $logoName);
            $data['logo_url'] = $logoName;
        }

        // Update data ke database
        $brand->update($data);

        return redirect()->back()->with('success', 'Brand data updated successfully!');
    }

    // ========================================================
    // 2. FUNGSI DESTROY (Proses Hapus Data & File)
    // ========================================================
    public function destroy(Brand $brand)
    {
        if ($brand->logo_url && file_exists(public_path('brand_image/' . basename($brand->logo_url)))) {
            unlink(public_path('brand_image/' . basename($brand->logo_url)));
        } elseif ($brand->logo_url && Storage::disk('public')->exists($brand->logo_url)) {
            Storage::disk('public')->delete($brand->logo_url);
        }

        $brand->delete();

        return redirect()->back()->with('success', 'Brand successfully moved to trash!');
    }

    public function publicIndex()
    {
        $brands = Brand::latest()->get();
        return view('brands.index', compact('brands'));
    }
}
