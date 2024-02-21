<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function fetchData(Request $request){
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $description = $request->input('description');
        $tags = $request->input('tags');
        $categories = $request->input('categories');
        $price_from = $request->input('price_from');
        $price_to = $request->input('categories');

        // Get data sort by Id
        if($id) {
            $product = Product::with(['product_category','product_galleries'])->find($id);

            if ($product) {
                $meta = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'data product berhasil diambil',
                ];
                return response()->json([
                    'meta' => $meta,
                    'data' => $product
                ]);
            }else{
                $meta = [
                    'code' => 404,
                    'status' => 'filed',
                    'message' => 'data product tidak ada',
                ];
                return response()->json([
                   'meta' => $meta,
                   'data' => null
                ]);
            }
        }

        $product = Product::with(['product_category','product_galleries']);
        // Get data sort by Name
        if($name) {
            $product->where('name', 'like', '%' .$name. '%');
        }
        // Get data sort by Description
        if($description) {
            $product->where('description', 'like', '%' .$description. '%');
        }
        // Get data sort by Tags
        if($tags) {
            $product->where('tags', 'like', '%' .$tags. '%');
        }
        // Get data sort by Price From
        if($price_from) {
            $product->where('price_from', '>=', $price_from );
        }
        // Get data sort by Price To
        if($price_to) {
            $product->where('price_to', '<=', $price_to);
        }
        // Get data sort by Category
        if($categories) {
            $product->where('categories', $categories);
        }

        $meta = [
            'code' => 200,
            'status' => 'success',
            'message' => 'data product berhasil diambil',
        ];
        return response()->json([
            'meta' => $meta,
            'data' => $product->paginate($limit)
        ]);
    }
}
