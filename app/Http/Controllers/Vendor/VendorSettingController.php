<?php

namespace App\Http\Controllers\Vendor;

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
use Validator;
use App;
use App\User;
use App\Vendor;
use App\Category;
use App\Variation;
use App\SubVariation;

class VendorSettingController extends Controller
{
    public function __construct()
	{
		$this->middleware('role:Vendor|Super Admin');
	}

	public function index($username)
	{
		$checkVendor = User::check_vendor($username);

		if ($checkVendor) {

			$vendor = User::where('username',$username)->firstOrFail();
			$vendor_details = $vendor->vendor_details;

			return view('admin.vendor-settings', array('vendor' => $vendor, 'vendor_details' => $vendor_details, 'id' => '0', 'username' => $username));
		}else{
			return redirect()->back()->with('log_status','Permission Denied!');
		}
	}

	public function update_vendor_settings($username, Request $request)
	{
		
		$validator = Validator::make($request->all(), [
			"store_name" => 'required|max:255',
			"address_1" => 'required',
			"phone" => 'required',
			"city" => 'required',
			"state" => 'required',
		]);

		if ($validator->fails()) {
			return redirect()
			->back()
			->withErrors($validator)
			->withInput();
		}
		// dd($request);

		$checkVendor = User::check_vendor($username);

		if ($checkVendor) {

			$vendor = User::where('username',$username)->firstOrFail();
			
			$slug = Vendor::createSlug($request['store_name'], $vendor->id);

			$vendorDetailsArray = ["store_name" => $request->store_name,
								  "slug" => $slug,
							      "address_1" => $request->address_1,
							      "address_2" => $request->address_2,
							      "phone" => $request->phone,
							      "mobile" => $request->mobile,
							      "city" => $request->city,
							      "state" => $request->state,
							      "zip_code" => $request->zip_code,
							      "opening_time" => $request->opening_time,
							      "closing_time" => $request->closing_time,
							      "delivery_fee" => isset($request->delivery_fee) ? $request->delivery_fee : 0,
							      "minimum_order" => isset($request->minimum_order) ? $request->minimum_order : 0,
							      "tax_rate_1" => $request->tax_rate_1 == '' ? 0 : $request->tax_rate_1,
							      "tax_rate_2" => $request->tax_rate_2 == '' ? 0 : $request->tax_rate_2,
							      "tax_rate_3" => $request->tax_rate_3 == '' ? 0 : $request->tax_rate_3,
							      "bottle_deposit_1_rate" => $request->bottle_deposit_1_rate == '' ? 0 : $request->bottle_deposit_1_rate,
							      "bottle_deposit_2_rate" => $request->bottle_deposit_2_rate == '' ? 0 : $request->bottle_deposit_2_rate,
							      "commission_percentage" => $request->commission_percentage == '' ? 0 : $request->commission_percentage,
							      "description" => $request->description,
							      "created_by" => Auth::user()->name
							  ];

			if ($request->hasFile('image')) {

			    $image = $request->file('image');
			    $validatedData = $request->validate([
			        'image' => 'required|mimes:jpg,jpeg,png|max:2048',
			    ]);

			    $oldvendor = $vendor->image;

			    $image = $request->file('image');
			    $filename = time().'.'.$image->getClientOriginalExtension();
			    
			    $folderPath = "public/vendors";
			    $thumbPath = "public/vendors/thumbs";

			    if(!file_exists($thumbPath)){
			        Storage::makeDirectory($thumbPath,0777,true,true);
			    }
			    
			    Storage::putFileAs($folderPath, new File($image), $filename);
			    
			    Vendor::resize_crop_images(1200, 800, $image, $thumbPath."/large_". $filename);
			    Vendor::resize_crop_images(900, 600, $image, $thumbPath."/small_". $filename);

			    Storage::delete($folderPath .'/'. $oldvendor);
			    Storage::delete($folderPath .'/thumbs/large_'. $oldvendor);
			    Storage::delete($folderPath .'/thumbs/small_'. $oldvendor);

			    $vendorDetailsArray['image'] = $filename;

			}

			$vendorDetailsUpdated = Vendor::updateOrCreate(
                                        ['user_id' => $vendor->id],
                                        $vendorDetailsArray
                            );


			if ($vendorDetailsUpdated) {
				return redirect()->to('vendor/'.$username.'/vendor-settings')->with('status', 'Vendor Details Updated Successfully!');
			}else{
				return redirect()->back()->with('error','Something went Wrong!');
			}
		}else{
			return redirect()->back()->with('log_status','Permission Denied!');
		}
	}
}
