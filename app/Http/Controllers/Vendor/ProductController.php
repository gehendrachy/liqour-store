<?php

namespace App\Http\Controllers\Vendor;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App;
use App\User;
use App\Product;
use App\Category;
use App\Variation;
use App\SubVariation;
use App\ProductVariation;

class ProductController extends Controller
{
    // protected $_username;

    public function __construct()
    {
        $this->middleware('role:Vendor|Super Admin');
    }



    public function add_products($username)
    {
        $checkVendor = User::check_vendor($username);

        if ($checkVendor) {

            $categories = $this->getFullListFromDB();

            return view('admin.vendor-add-products', array('categories' => $categories, 'id' => '0', 'username' => $username));

        }else{
            return redirect()->back()->with('log_status','Permission Denied!');
        }

    }

    public function getFullListFromDB($parent_id = 0)
    {
        $categories =  DB::table('categories')->where([['display', 1],['parent_id', $parent_id]])->select('id','title','parent_id', 'child')->orderBy('order_item')->get();

        foreach ($categories as &$value) {
            $subresult = $this->getFullListFromDB($value->id);

            if (count($subresult) > 0) {
                $value->children = $subresult;
            }
        }

        unset($value);

        return $categories;
    }

    // Slug check and create starts
    public function createSlug($title, $id = 0)
    {
        // Normalize the title
        $slug = str_slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedSlugs($slug, $id);

        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        for ($i = 1; $i <= 50; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        throw new \Exception('Can not create a unique slug');
    }

    protected function getRelatedSlugs($slug, $id = 0)
    {
        return Product::select('slug')->where('slug', 'like', $slug.'%')
        ->where('id', '<>', $id)
        ->get();
    }

    public function resize_crop_images($max_width, $max_height, $image, $filename){
        $imgSize = getimagesize($image);
        $width = $imgSize[0];
        $height = $imgSize[1];

        $width_new = round($height * $max_width / $max_height);
        $height_new = round($width * $max_height / $max_width);

        if ($width_new > $width) {
            //cut point by height
            $h_point = round(($height - $height_new) / 2);

            $cover = storage_path('app/'.$filename);
            Image::make($image)->crop($width, $height_new, 0, $h_point)->resize($max_width, $max_height)->save($cover);
        } else {
            //cut point by width
            $w_point = round(($width - $width_new) / 2);
            $cover = storage_path('app/'.$filename);
            Image::make($image)->crop($width_new, $height, $w_point, 0)->resize($max_width, $max_height)->save($cover);
        }

    }

    public function createproduct($username, Request $request)
    {
        
        // dd($username);

        $validator = Validator::make($request->all(), [ 
            "product_name" => 'required|max:255',
            "pack" => 'sometimes|required',
            // "pack.*" => 'sometimes|required',
            "size" => 'sometimes|required',
            "size.*" => 'sometimes|required|alpha_num'
        ]);

        if ($validator->fails()) {
            return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();
        }


        $max_order = DB::table('products')->max('order_item');

        $slug = $this->createSlug($request['product_name']);

        $path = public_path().'/storage/products/'.$slug;
        $folderPath = 'public/products/'.$slug;

        if (!file_exists($path)) {

            Storage::makeDirectory($folderPath,0777,true,true);

            if (!is_dir($path."/thumbs")) {
                Storage::makeDirectory($folderPath.'/thumbs',0777,true,true);
            }

        }

        if (!$request['featured']) {
            $request['featured'] = 0;
        }

        if (!$request['display']) {
            $request['display'] = 0;
        }

        $insertArray = array(   "product_name" => $request['product_name'],
                                "slug" => $slug,
                                "category_id" => $request['category_id'],
                                "order_item" => $max_order + 1,
                                "featured" => $request['featured'],
                                "display" => $request['display'],
                                "short_content" => $request['short_content'],
                                "long_content" => $request['long_content'],
                                "created_by" => Auth::user()->name,
                                "updated_by" => ""
                            );

        if ($request->hasFile('image')) {
            //Add the new photo
            $image = $request->file('image');
            $filename = time().'_m.'.$image->getClientOriginalExtension();
            Storage::putFileAs($folderPath, new File($image), $filename);

            $this->resize_crop_images(600, 900, $image, $folderPath."/thumbs/small_".$filename);
            $this->resize_crop_images(800, 1200, $image, $folderPath."/thumbs/large_".$filename);
            $this->resize_crop_images(270, 360, $image, $folderPath."/thumbs/thumb_".$filename);

            $insertArray['image'] = $filename;

        }

        if ($request->hasFile('other_images')) {
            //Add the new photo
            $otherImages = $request->file('other_images');
            foreach ($otherImages as $key => $other) {

                $filename_o = time().$key.'_.'.$other->getClientOriginalExtension();
                Storage::putFileAs($folderPath, new File($other), $filename_o);

                $this->resize_crop_images(600, 900, $other, $folderPath."/thumbs/small_".$filename_o);
                $this->resize_crop_images(800, 1200, $other, $folderPath."/thumbs/large_".$filename_o);
                $this->resize_crop_images(270, 360, $other, $folderPath."/thumbs/thumb_".$filename_o);
            }

        }

        $product = Product::create($insertArray);

        if (isset($request->pack)) {            

            if ($request->hasFile('variation_image')) {

                $pack = $request->pack;
                $size = $request->size;
                $container = $request->container;

                $productVariationArray = array();

                // Add Files
                $variationImage = $request->file('variation_image');
                $counterDuplicateVariations = 0;
                for($i=0; $i < count($pack); $i++){

                    $productVariationExists = ProductVariation::where([['product_id',$product->id], ['pack',$pack[$i]],['size',$size[$i]],['container',$container[$i]]])->exists();

                    if (!$productVariationExists) {

                        $product_variation = new ProductVariation();
                        $product_variation->product_id = $product->id;
                        $product_variation->pack = isset($pack[$i]) && $pack[$i] > 0 ? $pack[$i] : 1;
                        $product_variation->size = $size[$i];
                        $product_variation->container = $container[$i];

                        $filename_o = time().$i.'_.'.$variationImage[$i]->getClientOriginalExtension();
                        Storage::putFileAs($folderPath.'/', new File($variationImage[$i]), $filename_o);

                        $this->resize_crop_images(600, 900, $variationImage[$i], $folderPath."/thumbs/small_".$filename_o);
                        $this->resize_crop_images(800, 1200, $variationImage[$i], $folderPath."/thumbs/large_".$filename_o);
                        $this->resize_crop_images(270, 360, $variationImage[$i], $folderPath."/thumbs/thumb_".$filename_o);

                        $product_variation->image = $filename_o;
                        $product_variation->save();

                        // array_push($productVariationArray, array("pack" => $pack[$i],
                        //                                       "size" => $size[$i],
                        //                                       "container" => $container[$i],
                        //                                       "image" => $filename_o
                        //                                      ));
                    }else{
                        $counterDuplicateVariations++;
                    }

                }            
                // $product->product_variations()->createMany($productVariationArray);
            }
        }

        return redirect()->to('vendor/'.$username.'/inventory-products')->with('status', 'Product has been Added Successfully!');
    }

    public function add_variations($cIndex){
        if($cIndex){

            $responseText = '<div class="card-body pb-0 mb-1" id="variation'.$cIndex.'">
                                <div class="row">

                                    <div class="col-md-2">
                                        <small class="text-muted">
                                            <i class="fa fa-anchor"></i> &nbsp; Pack*
                                        </small>
                                        <div class="input-group mb-3">
                                            <input type="text" name="pack[]" placeholder="Pack"  class="form-control" onkeypress="return isNumberKey(event)">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <small class="text-muted">
                                            <i class="fa fa-anchor"></i> &nbsp; Size*
                                        </small>
                                        <div class="input-group mb-3">
                                            <input type="text" name="size[]" placeholder="Size" required class="form-control size_class">
                                        </div>

                                    </div>

                                    <div class="col-md-2">
                                        <small class="text-muted">
                                            <i class="fa fa-anchor"></i> &nbsp; Container*
                                        </small>
                                        <div class="mb-3">
                                            <select name="container[]" class="form-control" required>
                                                <option value="Bottle">Bottle</option>
                                                <option value="Plastic Bottle">Plastic Bottle</option>
                                                <option value="Can">Can</option>
                                            </select>
                                        </div>
                                        
                                    </div>

                                    <div class="col-md-4">
                                        <small class="text-muted">
                                            <i class="fa fa-anchor"></i> &nbsp; Upload Image*
                                        </small>
                                        <div class="input-group mb-3">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="variation_image[]" required>
                                                <label class="custom-file-label"><i class="fa fa-image"></i> Choose Image</label>
                                            </div>

                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2 text-right">
                                        <label></label>
                                        <button type="button" class="btn btn-sm btn-danger btn_remove" id="'.$cIndex.'">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                    </div>  
                                </div>
                            </div>';

            
            return $responseText;
        }
    }

}
