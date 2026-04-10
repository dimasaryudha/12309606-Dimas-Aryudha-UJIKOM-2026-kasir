<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,png,jpeg'
        ]);

        $price = str_replace('.', '', $request->price);

        $image = null;
        if ($request->file('image')) {
            $image = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'price' => $price,
            'stock' => $request->stock,
            'image' => $image
        ]);

        return redirect()->route('products.index')->with('success', 'Berhasil tambah produk');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'image' => 'nullable|image'
        ]);

        $price = str_replace('.', '', $request->price);

        if ($request->file('image')) {
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }

            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'price' => $price,
        ]);

        return redirect()->route('products.index')->with('success', 'Berhasil update');
    }

}
