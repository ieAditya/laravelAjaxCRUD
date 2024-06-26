<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{

    public function index()
    {
        return view('products.index');
    }
    public function getData()
    {
        return Product::where('is_deleted', false)->get();
    }
    public function getDataForModal(Request $request)
    {
        return Product::find($request->id);
    }
    public function createOrUpdate(Request $request)
    {
        if ($request->id == "") {
            $product = new Product;

            $product->name = $request->name;
            $product->description = $request->description;

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('products'), $imageName);

            $product->image = $imageName;

            $product->save();
        } else {
            $product = Product::find($request->id);
            $product->name = $request->name;
            $product->description = $request->description;

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('products'), $imageName);

            $product->image = $imageName;

            $product->update();
        }
        return response()->json([
            'success' => 'Success!'
        ]);
    }
    public function destroy(Request $request)
    {
        $product = Product::find($request->id);
        $product->is_deleted = true;
        $product->update();
        return response()->json([
            'success' => 'Record has been deleted successfully!'
        ]);
    }
}
