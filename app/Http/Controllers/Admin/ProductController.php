<?php

namespace App\Http\Controllers\Admin;

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
		$this->middleware('role:Super Admin');
	}

	public function index()
	{

		$categories = $this->getFullListFromDB();
		if (isset($_GET['catId'])) {
			
			$products = Product::where('category_id',$_GET['catId'])->orderBy('order_item')->paginate(50);
		}else{
			
			$products = Product::orderBy('order_item')->paginate(50);
		}

		return view('admin.products', array('products' => $products, 'categories' => $categories, 'id' => '0'));
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

	public function createproduct(Request $request)
	{
	    
		// dd($request);

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
								"brand" => $request['brand'],
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
                		//                                     	 "size" => $size[$i],
                		//                                     	 "container" => $container[$i],
                		//                                     	 "image" => $filename_o
                		//                                     	));
                	}else{
                		$counterDuplicateVariations++;
                	}

                }            
                // $product->product_variations()->createMany($productVariationArray);
            }
        }

		return redirect()->to('admin/products')->with('status', 'Product has been Added Successfully!');
	}

	public function editproduct($id)
	{	

		$categories = $this->getFullListFromDB();
		$product = Product::where('id' , base64_decode($id))->firstOrFail();

		return view('admin.products', array('product' => $product, 'id' => base64_decode($id), 'categories' => $categories));

	}

	public function updateproduct(Request $request){
        

		$validator = Validator::make($request->all(), [
			"product_name" => 'required|max:255',
			"pack" => 'sometimes|required',
			// "pack.*" => 'sometimes|required|numeric',
			"size" => 'sometimes|required',
			"size.*" => 'sometimes|required|alpha_num'
		]);

		if ($validator->fails()) {
			// echo 'failed';
			return redirect()->back()->withErrors($validator)->withInput();
		}

		// dd($_POST);

		$product = Product::findOrFail($request['id']);

		$product->product_name = $request['product_name'];
		$product->category_id = $request['category_id'];

		$slug = str_slug($request['product_name'], '-');
		$path = public_path().'/storage/products/'.$slug;

		if ($product->slug != $slug) {
			
			if (file_exists($path)) {
				Storage::move('public/products/'. $product->slug , 'public/products/'.$slug);
			}

			$product->slug = $this->createSlug($slug, $request['id']);
			$slug = $product->slug;
		}

		$folderPath = 'public/products/'. $slug;

		if (!file_exists($path)) {

			Storage::makeDirectory($folderPath,0777,true,true);

			if (!is_dir($path."/thumbs")) {
				Storage::makeDirectory($folderPath.'/thumbs',0777,true,true);
			}
		}

		if ($request->hasFile('image')) {
            //Add the new photo
			$image = $request->file('image');
			$filename = time().'_m.'.$image->getClientOriginalExtension();
			Storage::putFileAs($folderPath, new File($image), $filename);

			$this->resize_crop_images(600, 900, $image, $folderPath."/thumbs/small_".$filename);
			$this->resize_crop_images(800, 1200, $image, $folderPath."/thumbs/large_".$filename);
			$this->resize_crop_images(270, 360, $image, $folderPath."/thumbs/thumb_".$filename);

			$OldFilename = $product->image;

            //Update the database
			$product->image = $filename;

            //Delete the old photo
			Storage::delete($folderPath ."/".$OldFilename);
			Storage::delete($folderPath ."/thumbs/small_".$OldFilename);
			Storage::delete($folderPath ."/thumbs/large_".$OldFilename);
			Storage::delete($folderPath ."/thumbs/thumb_".$OldFilename);

		}

		if ($request->hasFile('other_images')) {
            //Add the new photo
			$otherImages = $request->file('other_images');
			foreach ($otherImages as $key => $other) {

				$filename = time().$key.'_.'.$other->getClientOriginalExtension();
				Storage::putFileAs($folderPath, new File($other), $filename);

				$this->resize_crop_images(600, 900, $other, $folderPath."/thumbs/small_".$filename);
				$this->resize_crop_images(800, 1200, $other, $folderPath."/thumbs/large_".$filename);
				$this->resize_crop_images(270, 360, $other, $folderPath."/thumbs/thumb_".$filename);
			}

		}

		// $product->sku = $request['sku'];

		if (!$request['featured']) {
			$request['featured'] = 0;
		}

		if (!$request['display']) {
			$request['display'] = 0;
		}

		$product->featured = $request['featured'];
		$product->display = $request['display'];
		$product->brand = $request['brand'];

		$product->short_content = $request['short_content'];
		$product->long_content = $request['long_content'];

		$product->updated_by = Auth::user()->name;
		$product->updated_at = date('Y-m-d H:i:s');
		$product->save();

		if (isset($request->pack)) {

        	if ($request->hasFile('variation_image')) {

        		$pack = $request->pack;
        		$size = $request->size;
        		$container = $request->container;
        		$productVariationArray = array();

                // Add Files
                $variationImage = $request->file('variation_image');

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
                		//                                     	 "size" => $size[$i],
                		//                                     	 "container" => $container[$i],
                		//                                     	 "image" => $filename_o
                		//                                     	));
                	}

                }            
                // $product->product_variations()->createMany($productVariationArray);
            }
        }

        if (isset($request->product_variation_id)) {

            $product_variation_id = $request->product_variation_id;
            $pack_db = $request->pack_db;
            $size_db = $request->size_db;
            $container_db = $request->container_db;
            
            $variationImageUpload = $request->file('variation_image_db');
            $productVariationUpdate = array();

            for($i=0; $i < count($pack_db); $i++){
            	
            	$product_variation = ProductVariation::find($product_variation_id[$i]);

            	$productVariationExists = ProductVariation::where([['id', '!=' , $product_variation->id], ['product_id',$product->id], ['pack',$pack_db[$i]],['size',$size_db[$i]],['container',$container_db[$i]]])->exists();

            	if (!$productVariationExists) {
        			$product_variation->pack = isset($pack_db[$i]) && $pack_db[$i] > 0 ? $pack_db[$i] : 1;
        			$product_variation->size = $size_db[$i];
        			$product_variation->container = $container_db[$i];
        		    
        		    if (isset($variationImageUpload[$product_variation_id[$i]])) {

        		    	$variationImage = $variationImageUpload[$product_variation_id[$i]];
        		        $oldImageName = $product_variation->image;

        		        $filename_o = time().$i.'.'.$variationImage->getClientOriginalExtension();

        		        Storage::putFileAs($folderPath.'/', new File($variationImage), $filename_o);

        		        $this->resize_crop_images(600, 900, $variationImage, $folderPath."/thumbs/small_".$filename_o);
        		        $this->resize_crop_images(800, 1200, $variationImage, $folderPath."/thumbs/large_".$filename_o);
        		        $this->resize_crop_images(270, 360, $variationImage, $folderPath."/thumbs/thumb_".$filename_o);

        		        $product_variation->image = $filename_o;

        		        Storage::delete($folderPath ."/".$oldImageName);
        		        Storage::delete($folderPath ."/thumbs/small_".$oldImageName);
        		        Storage::delete($folderPath ."/thumbs/large_".$oldImageName);
        		        Storage::delete($folderPath ."/thumbs/thumb_".$oldImageName);
        		    }

        		    $product_variation->save();    
            	}

                // ProductVariation::where('id',$product_variation_id[$i])->update($productVariationUpdate);
            }
        }
		
		return redirect()->to('admin/products')->with('status', 'Product Updated Successfully!');
	}


	public function delete($id){

		$product = Product::where('id' , base64_decode($id))->firstOrFail();
		
		if ($product->inventory_products->count() > 0) {
			return redirect()->back()->with('error','Can\'t Delete this Product. Vendors have already added it to their Inventory');
		}
		// dd('dfl');
		if ($product) {

			$productFolder = 'public/products/'.$product->slug;
			Storage::deleteDirectory($productFolder);

			$product->product_variations()->delete();
			$product->delete();

			return redirect()->back()->with('status', 'Product Deleted Successfully!');

		}else{

			return redirect()->back()->with('status', 'Something Went Wrong!');
		}
	}

	public function set_order(Request $request){

		$list_order = $request->list_order;

		$i = 1 ;
		foreach($list_order as $id) {
			$updateData = array("order_item" => $i);
			Product::where('id', $id)->update($updateData);

			$i++ ;
		}
		$data = array('status'=> 'success');
		echo json_encode($data);
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
                					    
                					    <input type="text" name="size[]" placeholder="Size" required class="form-control size_class">
                					    

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

    public function show_sub_variations(Request $request){
        $variation_id = $request->variation_id;
        $select_id = $request->select_id;
        $dbSubVariation = 0;


        if($variation_id){

            $subVariations = SubVariation::where('variation_id', $variation_id)->get();
            $responseText ='<select class="custom-select variation-select2"  name="sub_variation_id[]" required>';

            if ($request->prod_var_id != '') {
                $responseText ='<select class="custom-select variation-select2"  name="sub_variation_id_db[]" required>';                         
                $dbSubVariation = ProductVariation::where('id',$request->prod_var_id)->first()->sub_variation_id;

            }

            foreach ($subVariations as $key => $subVar) {
                $selected = $subVar->id == $dbSubVariation ? "selected" : "";
                $responseText .= '<option '.$selected. ' value="'.$subVar->id .'">'.$subVar->title.'</option>';
            }

            $responseText .= '</select>';

        }else{
            $responseText = '<select class="custom-select" required><option selected disabled>Select Variation First</option></select>';
        }

        return $responseText;
    }

    public function delete_product_variation($product_variation_id){

        $product_variation = ProductVariation::findOrFail(base64_decode($product_variation_id));
        $OldFilename = $product_variation->image;

        $folderPath = 'public/products/'.$product_variation->product->slug;
        if ($product_variation->delete()) {

        	Storage::delete($folderPath ."/".$OldFilename);
			Storage::delete($folderPath ."/thumbs/small_".$OldFilename);
			Storage::delete($folderPath ."/thumbs/large_".$OldFilename);
			Storage::delete($folderPath ."/thumbs/thumb_".$OldFilename);
			
            return redirect()->back()->with('status','Product Variation Deleted Successfully!!');
        }else{
            return redirect()->back()->with('status','Something went wrong!!');
        }
    }

}
