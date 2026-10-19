<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'supplier'])->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('admin.products.create', compact('categories', 'suppliers'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            $request->file('image')->storeAs('public/products', $filename);
            $data['image'] = $filename;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('admin.products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::delete('public/products/' . $product->image);
            }

            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            $request->file('image')->storeAs('public/products', $filename);
            $data['image'] = $filename;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // Don't actually delete if there are transactions. Instead set is_active to false.
        // Or if we must delete, handle image deletion too.
        if ($product->transactionDetails()->exists()) {
            $product->update(['is_active' => false]);
            return redirect()->route('admin.products.index')->with('success', 'Produk dinonaktifkan karena sudah memiliki riwayat transaksi.');
        }

        if ($product->image) {
            Storage::delete('public/products/' . $product->image);
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
