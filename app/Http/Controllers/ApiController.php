<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Common;
use App\Models\Product;
use App\Models\Category;

class ApiController extends Controller
{
    public function getCategories()
    {
        $common_model = new Common();
        $all_categories = $common_model->allCategories();

        $category_array = array();
        foreach ($all_categories as $category_data) {
            $cid = $category_data->category_row_id;

            if ($category_data->parent_id == 0) {
                $category_array[$cid]['category_name'] = $category_data->category_name;
                $category_array[$cid]['category_image'] = $category_data->category_image;
            } else {
                $pcount = Product::where('category_id', $cid)->count();

                $category_array[$category_data->parent_id]['subcategory'][$cid]['category_name'] = $category_data->category_name;
                $category_array[$category_data->parent_id]['subcategory'][$cid]['category_image'] = $category_data->category_image;
                $category_array[$category_data->parent_id]['subcategory'][$cid]['product_count'] = $pcount;
            }
        }

        if (isset($category_array)) {
            return response()->json($category_array);
        } else {
            return response()->json(['error' => 'No Categories Found'], 500);
        }
    }

    public function getProductsByCategoryId($cid){
        if(is_numeric($cid) && $cid > 0){
            $products = Product::with('product_images', 'product_inventory', 'product_attribute', 'getCategory')->where('category_id', $cid)->get();

            if(isset($products)){
                return response()->json($products);
            }else{
                return response()->json(['error' => 'Wrong Category ID provided'],500);
            }
        }else{
            return response()->json(['error' => 'Category Id is not valid, Id Should be Numeric'],500);
        }
    }

    public function getAllFeaturedCategory(){

        $featured_category = Category::where('is_featured', 1)->withCount('total_products')->get();
        if(isset($featured_category)){
            return response()->json($featured_category);
        } else {
            return response()->json(['error' => 'No featured category found'], 500);
        }
    }

    public function search(Request $request){
        // Validate the search query
        $request->validate([
            'query' => 'required|string|min:1'
        ]);

        // Search products based on the query
        $query = $request->input('query');

        $products = Product::where('product_title', 'like', "%$query%")
                           ->orWhere('short_description', 'like', "%$query%")
                           ->get();

        return response()->json($products);
    }
}
