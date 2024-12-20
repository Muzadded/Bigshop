<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common;
use Illuminate\Support\Facades\Auth;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductDiscount;
use App\Models\ProductImage;
use App\Models\ProductInventory;
use App\Models\ProductAttribute;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $common_model = new Common();
        
        $all_categories = $common_model->allCategories();
        $all_attributes = $common_model->allAttributes();

        return view('admin.products.create', compact('all_categories', 'all_attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);

        if($request->isMethod('post')){

            $validated = $request->validate([
                'product_name' => 'required',
                'parent_id' => 'required',
                'product_sku' => 'required'
            ]);

            $product_db = new Product();
            $product_db->product_title = $request->product_name;
            $product_db->category_id = $request->parent_id;
            $product_db->short_description = $request->short_description ? $request->short_description : null;
            $product_db->long_description = $request->long_description ? $request->long_description : null;
            $product_db->brand_id = $request->brand_id ? $request->brand_id : null;
            $product_db->product_tags = $request->product_tags ? $request->product_tags : null;
            $product_db->product_model = $request->product_model ? $request->product_model : null;
            $product_db->product_sku = $request->product_sku;
            $product_db->product_price = $request->product_price;
            $product_db->product_unit = $request->product_unit;

            $product_db->is_featured = $request->is_featured ? 1 : 0;
            $product_db->top_selling = $request->top_selling ? 1 : 0;
            $product_db->is_refundable = $request->is_refundable ? 1 : 0;
            $product_db->created_by = Auth::guard('admin')->user()->id;
            // $product_db->created_by = $admin_info->id;

            $product_db->save();
            $pid = $product_db->product_id;

            //upload feature & gallery images
            if($pid && isset($request->feature_image)){
                $feature_image   = $request->file('feature_image');
                $filename        = time().'_'.$feature_image->getClientOriginalName();
                $product_image = new ProductImage();
                $product_image->product_id      = $pid;
                $product_image->feature_image   = $filename;

                $feature_image->move(public_path('uploads/products/').$pid.'/original/',$filename);
                $image_resize = Image::read(public_path('uploads/products/').$pid.'/original/'.$filename);
                $image_resize->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $save_path = public_path('uploads/products/').$pid.'/thumbnail/';
                if (!file_exists($save_path)) {
                    mkdir($save_path, 777, true);
                }
                $image_resize->save(public_path('uploads/products/').$pid.'/thumbnail/'.$filename);

                //upload gallery images
                if(isset($request->gallery_images)){
                    $gallery_images = $request->gallery_images;
                    //dd($gallery_images);
                    $galleryimg = array();
                    foreach ($gallery_images as $gdata) {
                        $gfilename         = time().'_'.$gdata->getClientOriginalName();
                        $galleryimg[]      = $gfilename;
                        $gdata->move(public_path('uploads/products/').$pid.'/gallery_images/',$gfilename);
                    }
                    $product_image->gallery_images = json_encode($galleryimg);
                }
                $product_image->save();
            }

            if($pid && isset($request->attr_price)){
                $total_quantity = 0;
                $attribute_price = $request->attr_price;
                $attribute_quantity = $request->attr_quantity;
                foreach ($attribute_price as $attribute_title => $value) {
                    $total_quantity += $attribute_quantity[$attribute_title];
                    $product_attribute = new ProductAttribute();
                    $product_attribute->product_id = $pid;
                    $product_attribute->attribute_title = $attribute_title;
                    $product_attribute->attribute_price = $value;
                    $product_attribute->attribute_quantity = $attribute_quantity[$attribute_title];
                    $product_attribute->save();
                }

                //insert/update stock quantity
                $product_inventory = new ProductInventory();
                $product_inventory->product_id = $pid;
                $product_inventory->stock_amount = $total_quantity;
                $product_inventory->save();
            }

            Alert::success('Product Added Successfully!', 'success');    
            return redirect()->route('products.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
