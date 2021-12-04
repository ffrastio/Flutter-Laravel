<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit',5);
        $name = $request->input('name');
        $description = $request->input('description');
        $categories = $request->input('categories');
        $tags = $request->input('tags');
        $price = $request->input('price_from');
        $price = $request->input('price_to');

        if ($id) {
            $product = Product::with(['category', 'galleries'])->find($id);

            if ($product) {
                return ResponseFormatter::success(
                    $product,
                    'Data Produk Berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    $product,
                    'Data Produk Tidak berhasil diambil',
                    404
                );
            }
        }

        $product = Product::with(['category', 'galleries']);

        if ($name) {
            $product->where('name', 'like', '%' . $name . '%');
        }
        if ($description) {
            $product->where('description', 'like', '%' . $description . '%');
        }
        if ($tags) {
            $product->where('tags', 'like', '%' . $tags . '%');
        }
        if ($price) {
            $product->where('price', '>=', $price);
        }
        if ($price) {
            $product->where('price', '<=', $price);
        }
        if ($categories) {
            $product->where('categories', $categories);
        }

        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data Berhasil di ambil'
        );
    }
}
