<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('sort_order')->paginate(15);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string|max:1000',
            'estimated_price' => 'required|string|max:255',
            'is_active'       => 'boolean',
            'sort_order'      => 'integer|min:0',
        ], [
            'name.required'            => 'Nama layanan wajib diisi.',
            'estimated_price.required' => 'Estimasi harga wajib diisi.',
        ]);

        $validated['is_active']   = $request->boolean('is_active', true);
        $validated['sort_order']  = $request->input('sort_order', 0);

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan <strong>' . $validated['name'] . '</strong> berhasil ditambahkan.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string|max:1000',
            'estimated_price' => 'required|string|max:255',
            'is_active'       => 'boolean',
            'sort_order'      => 'integer|min:0',
        ]);

        $validated['is_active']  = $request->boolean('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan <strong>' . $service->name . '</strong> berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        if ($service->bookings()->count() > 0) {
            return back()->with('error', 'Layanan tidak dapat dihapus karena masih memiliki data booking.');
        }

        $name = $service->name;
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', "Layanan <strong>{$name}</strong> berhasil dihapus.");
    }
}
