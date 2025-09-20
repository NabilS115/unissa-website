<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Merchandise;

class CatalogController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'category' => 'required|string|max:255',
            'img' => 'required|image|max:2048',
            'type' => 'required|in:food,merch',
        ]);
        $path = $request->file('img')->store('catalog', 'public');
        $imgPath = '/storage/' . $path;

        if ($request->type === 'food') {
            Food::create([
                'name' => $request->name,
                'desc' => $request->desc,
                'category' => $request->category,
                'img' => $imgPath,
                'type' => $request->type,
            ]);
        } else {
            Merchandise::create([
                'name' => $request->name,
                'desc' => $request->desc,
                'category' => $request->category,
                'img' => $imgPath,
                'type' => $request->type,
            ]);
        }
        return back()->with('success', 'Product added!');
    }

    public function edit(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'category' => 'required|string|max:255',
            'img' => 'nullable|image|max:2048',
        ]);
        // Example: $product = Product::findOrFail($id);
        // Update fields
        // $product->name = $request->name;
        // $product->desc = $request->desc;
        // $product->category = $request->category;
        // if ($request->hasFile('img')) {
        //     $path = $request->file('img')->store('catalog', 'public');
        //     $product->img = '/storage/' . $path;
        // }
        // $product->save();
        return back()->with('success', 'Product updated!');
    }

    public function upload(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'type' => 'required|in:food,merch',
        ]);
        $path = $request->file('image')->store('catalog', 'public');
        // Example: Save image path and type to your model/table
        // ProductImage::create([
        //     'img' => '/storage/' . $path,
        //     'type' => $request->type,
        // ]);
        return back()->with('success', 'Image uploaded!');
    }
}