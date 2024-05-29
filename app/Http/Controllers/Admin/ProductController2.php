<?php

namespace App\Http\Controllers\Admin;


use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App;
use App\User;
use App\Product;
use App\Category;
use App\Variation;
use App\SubVariation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

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
		$products = Product::orderBy('order_item')->paginate(10);

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
	    // var_dump(session()->get('vendorID'));
	    // echo "<pre>";
	    // var_dump(array_values($request->var_name));
	    // var_dump(array_values($request->price));
	    // dd(array_values($request->parent_var_name));
		// dd($request);

		$validator = Validator::make($request->all(), [
			"title" => 'required|max:255',
			"sku" => 'required',
			"image" => 'required',
		]);

		if ($validator->fails()) {
			return redirect()
			->back()
			->withErrors($validator)
			->withInput();
		}

		$product = new Product();

		$max_order = DB::table('products')->max('order_item');

		$product->title = $request['title'];

		$product->slug = $this->createSlug($request['title']);

		$path = public_path().'/storage/products/'.$product->slug;
		$folderPath = 'public/products/'.$product->slug;

		if (!file_exists($path)) {

			Storage::makeDirectory($folderPath,0777,true,true);

			if (!is_dir($path."/thumbs")) {
				Storage::makeDirectory($folderPath.'/thumbs',0777,true,true);
			}

		}

		if ($request->hasFile('image')) {
                //Add the new photo
			$image = $request->file('image');
			$filename = time().'.'.$image->getClientOriginalExtension();
			Storage::putFileAs($folderPath, new File($image), $filename);

			$this->resize_crop_images(600, 800, $image, $folderPath."/thumbs/small_".$filename);
			$this->resize_crop_images(900, 1200, $image, $folderPath."/thumbs/large_".$filename);
			$this->resize_crop_images(270, 360, $image, $folderPath."/thumbs/thumb_".$filename);

		}

		if ($request->hasFile('other_images')) {
                //Add the new photo
			$otherImages = $request->file('other_images');
			foreach ($otherImages as $key => $other) {

				$filename_o = time().$key.'_.'.$other->getClientOriginalExtension();
				Storage::putFileAs($folderPath, new File($other), $filename_o);

				$this->resize_crop_images(600, 800, $other, $folderPath."/thumbs/small_".$filename_o);
				$this->resize_crop_images(900, 1200, $other, $folderPath."/thumbs/large_".$filename_o);
				$this->resize_crop_images(270, 360, $other, $folderPath."/thumbs/thumb_".$filename_o);
			}

		}

		$product->image = isset($filename) ? $filename : '';

		$product->sku = $request['sku'];
		$product->order_item = $max_order + 1;

		if (!$request['featured']) {
			$request['featured'] = 0;
		}

		if (!$request['display']) {
			$request['display'] = 0;
		}


		$product->featured = $request['featured'];
		$product->display = $request['display'];
		$product->stockStatus = $request['stockStatus'];
		$product->stockQty = $request['stockQty'];

		$product->originalPrice = $request['originalPrice'];
		$product->discountedPrice = $request['discountedPrice'];
		$product->summary = $request['summary'];
		$product->description = $request['description'];
		$product->created_by = Auth::user()->name;
		$product->updated_by = "";

		$product->save();


		if ($request->variation_type == 1) {

			$childVarNames = array_values($request->var_name);
			$priceValues = array_values($request->price);

			for ($i=0; $i < count($childVarNames); $i++) {
				$this->save_product_variations($product->id, $childVarNames[$i], $priceValues[$i], 0, 0);
			}

		}elseif ($request->variation_type == 2) {

			$parentVarNames = array_values($request->parent_var_name);
			$childVarNames = array_values($request->var_name);
			$priceValues = array_values($request->price);

			for ($i=0; $i < count($parentVarNames); $i++) {
                    // dd($product->id);
				$pVariationId = $this->save_product_variations($product->id, $parentVarNames[$i], 0, 0, 1);
				for ($j=0; $j < count($childVarNames[$i]); $j++) {

					$this->save_product_variations($product->id, $childVarNames[$i][$j], $priceValues[$i][$j], $pVariationId, 0);

				}
			}
		}


		return redirect()->to('admin/products')->with('status', 'Product added Successfully!');
	}

	public function editproduct($username, $id)
	{
		$checkVendor = User::check_vendor($username);

		if ($checkVendor) {
			$categories = $this->getFullListFromDB();
			$product = Product::where('id' , base64_decode($id))->firstOrFail();

			$variations = collect($product->variation);
            // dd($variations);
			switch ($product->variation_type) {
				case '1':
				$productVariations = $variations;
				break;
				case '2':
				$productVariations = $variations->where('child',1);

				foreach ($productVariations as $key => $par) {
					$productVariations[$key]['children'] = $variations->where('parent_id', $par->id);
				}
				break;

				default:
				$productVariations = '';
				break;
			}

			$selectedCategories = [];
			foreach ($product->category as $category) {
				array_push($selectedCategories, $category->id);
			}
			return view('admin.vendor-edit-products', array('product' => $product, 'id' => base64_decode($id), 'categories' => $categories, 'username' => $username, 'selectedCategories' => $selectedCategories, "productVariations" => $productVariations, 'variationCount' => count($variations) ));
		}else{
			return redirect()->back()->with('log_status','Sorry, You are not authorized!');
		}

	}

	public function updateproduct(Request $request){
        // dd($request);

		$validator = Validator::make($request->all(), [
			"title" => 'required|max:255',
			"sku" => 'required',
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$product = Product::findOrFail($request['id']);

        // if ($product->variation_type != $request->variation_type) {
        //     Variation::where('product_id',$request->id)->delete();
        // }

		$product->title = $request['title'];

		$slug = str_slug($request['title'], '-');
		$path = public_path().'/storage/products/'.$product->slug;

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
			$filename = time().'.'.$image->getClientOriginalExtension();
			Storage::putFileAs($folderPath, new File($image), $filename);

			$this->resize_crop_images(600, 800, $image, $folderPath."/thumbs/small_".$filename);
			$this->resize_crop_images(900, 1200, $image, $folderPath."/thumbs/large_".$filename);
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

				$this->resize_crop_images(600, 800, $other, $folderPath."/thumbs/small_".$filename);
				$this->resize_crop_images(900, 1200, $other, $folderPath."/thumbs/large_".$filename);
				$this->resize_crop_images(270, 360, $other, $folderPath."/thumbs/thumb_".$filename);
			}

		}

		$product->sku = $request['sku'];

		if (!$request['variation_type']) {
			$request['variation_type'] = 0;
		}

		if (!$request['featured']) {
			$request['featured'] = 0;
		}

		if (!$request['display']) {
			$request['display'] = 0;
		}

		if (!$request['stockStatus']) {
			$request['stockStatus'] = 0;
		}

		if (!$request['stockQty']) {
			$request['stockQty'] = 0;
		}

		$product->featured = $request['featured'];
		$product->display = $request['display'];
		$product->stockStatus = $request['stockStatus'];
		$product->stockQty = $request['stockQty'];

		$product->originalPrice = $request['originalPrice'];
		$product->discountedPrice = $request['discountedPrice'];
		$product->summary = $request['summary'];
		$product->description = $request['description'];
		$product->updated_by = Auth::user()->name;
		$product->updated_at = date('Y-m-d H:i:s');

		$product->save();

		$product->category()->sync($request->categories);

		if ($request->variation_type == 1) {

			if (isset($request->var_name)) {

				$childVarNames = array_values($request->var_name);
				$priceValues = array_values($request->price);

				for ($i=0; $i < count($childVarNames); $i++) {
					$this->save_product_variations($request->id, $childVarNames[$i], $priceValues[$i], 0, 0);
				}
			}

			if ($request->variation_id_db) {
				$childVarNamesDB = array_values($request->var_name_db);
				$priceValuesDB = array_values($request->price_db);
				$variationIdDB = $request->variation_id_db;

				for ($i=0; $i < count($childVarNamesDB); $i++) {
					$this->update_product_variations($variationIdDB[$i], $childVarNamesDB[$i], $priceValuesDB[$i]);
				}
			}


		}elseif ($request->variation_type == 2) {
			if (isset($request->parent_var_name)) {
				$parentVarNames = $request->parent_var_name;
				$childVarNames = $request->var_name;
				$priceValues = $request->price;


				foreach ($parentVarNames as $i => $par) {
					$pVariationId = $this->save_product_variations($request->id, $parentVarNames[$i], 0, 0, 1);
                    // echo $parentVarNames[$i]."<br>";
					foreach ($childVarNames[$i] as $j => $child) {

						$this->save_product_variations($request->id, $childVarNames[$i][$j], $priceValues[$i][$j], $pVariationId, 0);
                        // echo "---->".$childVarNames[$i][$j]."->".$priceValues[$i][$j]."<br>";
					}
				}
			}

			if (isset($request->parent_variation_id_db)) {
				$parentVarNamesDB = $request->parent_var_name_db;
				$childVarNamesDB = $request->var_name_db;
				$priceValuesDB = $request->price_db;
				$parentVarIdDB = $request->parent_variation_id_db;
				$childVarIdDB = $request->child_variation_id_db;

				$childVarNames = $request->var_name;
				$priceValues = $request->price;


				foreach ($parentVarNamesDB as $i => $parDB) {
					$pVariationId = $this->update_product_variations($parentVarIdDB[$i], $parentVarNamesDB[$i], 0);
                    // echo $parentVarIdDB[$i]."-->".$parentVarNamesDB[$i]."<br>";

					foreach ($childVarNamesDB[$i] as $j => $childDB) {

						$this->update_product_variations($childVarIdDB[$i][$j], $childVarNamesDB[$i][$j], $priceValuesDB[$i][$j]);
                        // echo $childVarIdDB[$i][$j]."---->".$childVarNamesDB[$i][$j]."->".$priceValuesDB[$i][$j]."<br>";
					}

					if (isset($childVarNames[$i])) {
						foreach ($childVarNames[$i] as $k => $child) {

							$this->save_product_variations($request->id, $childVarNames[$i][$k], $priceValues[$i][$k], $parentVarIdDB[$i], 0);
                            // echo "---->".$childVarNames[$i][$k]."->".$priceValues[$i][$k]."<br>";
						}
					}
				}
			}
		}
		return redirect()->to('admin/products')->with('status', 'Product Updated Successfully!');
	}


	public function delete($id){

		$product = Product::where('id' , base64_decode($id))->firstOrFail();
		dd($product);
		if ($product) {

			$productFolder = 'public/products/'.$product->slug;
			Storage::deleteDirectory($productFolder);

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

        	$variations = Variation::where('display',1)->orderBy('order_item')->get();

        	$responseText = '<div class="col-md-4 mb-3" id="variation'.$cIndex.'">
        					<div class="card-body pb-0">
                				<div class="row">
                					<div class="col-md-12">
                						<small class="text-muted">
                    		                <i class="fa fa-anchor"></i> &nbsp; Variation*
                    		            </small>
                            		    <div class="input-group mb-3">
                            		        <select class="form-control select_variation" name="variation_id['.$cIndex.']" required id="'.$cIndex.'">

                            		        	<option selected disabled>Select Variation</option>';

                            		        	foreach($variations as $var){
		                                            $responseText .= '<option value="'.$var->id.'">'. $var->title.'</option>';
		                                        }
	                                        $responseText .='</select>
                            		    </div>
                					</div>
                					<div class="col-md-12">
                						<small class="text-muted">
                    		                <i class="fa fa-anchor"></i> &nbsp; Variations*
                    		            </small>
                                        <div class="mb-3 multiselect_div" id="subVariationSelect'.$cIndex.'">
                                            <select class="form-control" required>

                                                <option selected disabled>Select Variation First</option>
                                            </select>
                                        </div>
                            		    
                					</div>

                					<div class="col-md-12">
	                                    <div class="input-group mb-3">
	                                        
	                                        <div class="custom-file">
	                                            <input type="file" class="custom-file-input" name="key_upload[]" required>
	                                            <label class="custom-file-label"><i class="fa fa-image"></i> Upload Image</label>
	                                        </div>
	                                        
	                                    </div>
	                                </div>
                					
                            		
                            			<button style="position: absolute; top: -9px; padding: 4px; color: #fff;border-radius: 50%; opacity: 1;" type="button" class="btn btn-sm btn-danger btn_remove" id="'.$cIndex.'">
			                    				<i class="btn-danger close fa fa-trash"></i>
			                    			</button>
                            		
                				</div>
            				</div>
            				</div>
            				';

        	
            return $responseText;
        }
    }

    public function show_sub_variations(Request $request){
        $variation_id = $request->variation_id;
        $select_id = $request->select_id;
        $dbSubVariation = 0;


        if($variation_id){

            $subVariations = SubVariation::where('variation_id', $variation_id)->get();
            $responseText ='<select class="form-control"  name="sub_variations['.$select_id.'][]" required>';

            if ($request->prod_var_id != '') {
                $responseText ='<select class="form-control"  name="sub_variations_db['.$select_id.'][]" required>';                         
                $dbSubVariation = ProductVariation::where('id',$request->prod_var_id)->first()->sub_variation_id;

            }

            foreach ($subVariations as $key => $subVar) {
                $selected = $subVar->id == $dbSubVariation ? "selected" : "";
                $responseText .= '<option '.$selected. ' value="'.$subVar->id .'">'.$subVar->title.'</option>';
            }

            $responseText .= '</select>';

        }else{
            $responseText = '<select class="form-control" required><option selected disabled>Select Variation First</option></select>';
        }

        return $responseText;
    }

    public function delete_product_variation($product_variation_id){
        $product_variation = ProductVariation::findOrFail(base64_decode($product_variation_id));

        if ($product_variation->delete()) {
            return redirect()->back()->with('status','Product Variation Deleted Successfully!!');
        }else{
            return redirect()->back()->with('status','Something went wrong!!');
        }
    }

}
