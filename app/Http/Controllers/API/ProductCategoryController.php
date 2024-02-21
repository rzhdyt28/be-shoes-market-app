<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
     public function fetchData(Request $request){
        $id = $request->input('id');
        $limit = $request->input('limit');
                    $name = $request->input('name');
        $show_product = $request->input('show_product');

        // Get data sort by Id
        if($id) {
            $categories = ProductCategory::with(['products'])->find($id);

            if ($categories) {
                $meta = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'data kategori berhasil diambil',
                ];
                return response()->json([
                    'meta' => $meta,
                    'data' => $categories
                ]);
            }else{
                $meta = [
                    'code' => 404,
                    'status' => 'filed',
                    'message' => 'data kategori tidak ada',
                ];
                return response()->json([
                'meta' => $meta,
                'data' => null
                ]);
            }
        }

        $categories = ProductCategory::query();
        // Get data sort by Name
        if($name) {
            $categories->where('name', 'like', '%' .$name. '%');
        }
        // Get data sort by show_product
        if($show_product) {
            $categories->with(['products']);
        }

        // FETCH DATA
        $meta = [
            'code' => 200,
            'status' => 'success',
            'message' => 'data kategori berhasil diambil',
        ];
        return response()->json([
            'meta' => $meta,
            'data' => $categories->paginate($limit)
        ]);
    }

}
