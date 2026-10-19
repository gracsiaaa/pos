<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSupplierRequest;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('products')->get();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        Supplier::create($request->validated());
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(StoreSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        // On delete cascade is set null for products. So we can just delete it.
        $supplier->delete();
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
