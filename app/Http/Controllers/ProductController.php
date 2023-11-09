<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();;
        if ($products) {
            return response()->json($products);
        } else {
            return response()->json(['message' => 'No products found'], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg,gif,svg,jfif',
        ]);

        $product = new Product();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $allowedFileExtensions = array('jpg', 'png', 'pdf');
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedFileExtensions);

            if ($check) {
                $fileName = time() . $file->getClientOriginalName();
                $file->move('images', $fileName);
                $product->image = $fileName;
            }
        }

        $product->name = $request->input('name');

        $status = $product->save();

        if ($status) {
            return response()->json($product, 201);
        } else {
            return response()->json(['message' => 'Product not created'], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);;
        if ($product) {
            return response()->json($product);
        } else {
            return response()->json(['message' => 'No product found'], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg,gif,svg,jfif',
        ]);

        $product = Product::find($id);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $allowedFileExtensions = array('jpg', 'png', 'pdf');
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedFileExtensions);

            if ($check) {
                $fileName = time() . $file->getClientOriginalName();
                $file->move('images', $fileName);
                $product->image = $fileName;
            }
        }

        $product->name = $request->input('name');

        $status = $product->save();

        if ($status) {
            return response()->json($product, 201);
        } else {
            return response()->json(['message' => 'Product not created'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);;
        $status = $product->delete();
        if ($status) {
            return response()->json(['message' => 'Product Deleted'], 200);
        } else {
            return response()->json(['message' => 'No product found'], 404);
        }
    }
}
