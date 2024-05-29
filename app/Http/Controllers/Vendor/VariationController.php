<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Variation;
use App\SubVariation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;

class VariationController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Vendor|Super Admin');
    }

    public function add_variations($username)
    {
        $checkVendor = User::check_vendor($username);

        if ($checkVendor) {

            return view('admin.vendor-add-variations', array('id' => '0', 'username' => $username));

        }else{
            return redirect()->back()->with('log_status','Permission Denied!');
        }

    }

    // Slug check and create starts
	public function createSlug($title, $id = 0)
    {
        $slug = str_slug($title);

        $allSlugs = $this->getRelatedSlugs($slug, $id);

        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }
        
        throw new \Exception('Can not create a unique slug');
    }
    
    protected function getRelatedSlugs($slug, $id = 0)
    {
        return Variation::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }

    public function create($username, Request $request)
    {
    	// dd($username);
        $variation = new Variation();
        $max_order = DB::table('variations')->max('order_item');

        $max_order = $max_order == '' ? 0 : $max_order;

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'sub_variations' => 'required|array|min:1'
        ]);

        $request['display'] = isset($request['display']) ? $request['display'] : '0';

        $variation->title = $request->input('title');
        $variation->slug = $this->createSlug($request->input('title'));
        $variation->display = 1;
        $variation->order_item = $max_order + 1;
        $variation->created_by = Auth::user()->name;
        $variation->updated_by = '';
        $subVariationArray = array();

        for($i=0; $i<count($request->sub_variations); $i++){
        	array_push($subVariationArray, array("title" => $request->sub_variations[$i]));
        }
        // dd($subVariationArray);

        if ($variation->save()) {
        	$variation->sub_variation()->createMany($subVariationArray);

        	return redirect()->to('vendor/'.$username.'/add-products')->with('status', 'Variation Added Successfully!');        	
        }else{
        	return redirect()->back()->with('status', 'Something went Wrong!');	
        }

    }

    public function edit($id)
    {
    	$id = base64_decode($id);
        $variation = Variation::findOrFail($id);
        return view('admin.variations', array('variation' => $variation, 'id' => $id));
    }

    public function set_order(Request $request){
        $list_order = $request['list_order'];
        
        // $list = explode(',' , $list_order);
        $i = 1 ;
        foreach($list_order as $id) {
            $updateData = array("order_item" => $i);
            Variation::where('id', $id)->update($updateData);
            $i++ ;
        }
        $data = array('status'=> 'success');
		echo json_encode($data);
    }

    public function update(Request $request)
    {
        // dd($_POST);
        $variation = Variation::find($request->id);

        $validatedData = $request->validate([
            'title' => 'required|max:255'
        ]);

        if ($request['display'] == '') {
            $request['display'] = 0;
        }

        $variation->title = $request['title'];
        $variation->slug = $this->createSlug($request['title'], $request['id']);
        $variation->display = $request['display'];
        $variation->updated_by = Auth::user()->name;

        if ($variation->save()) {

            if (isset($request->sub_variations)) {

                $subVariationArray = array();

                for($i=0; $i<count($request->sub_variations); $i++){
                    array_push($subVariationArray, array("title" => $request->sub_variations[$i]));
                }   

                $variation->sub_variation()->createMany($subVariationArray);
            }

            if (isset($request->sub_variation_id)) {
                for ($i=0; $i < count($request->sub_variation_id); $i++) { 
                    $sub_variation = SubVariation::find($request->sub_variation_id[$i]);
                    $sub_variation->title = $request->sub_variation_db[$i];
                    $sub_variation->updated_at = date('Y-m-d H:i:s');
                    $sub_variation->save();
                }
            }

			return redirect()->to('/admin/variations')->with('status', 'Variation Updated Successfully!');	        	
        }else{
        	return redirect()->back()->with('status', 'Something went Wrong!');	
        }

    }
    
    public function delete($id)
    {
        $variation = Variation::findOrFail(base64_decode($id));

        if ($variation->delete()) {
            
            $sub_variations = SubVariation::where('variation_id',base64_decode($id));
            $sub_variations->delete();

            return redirect()->back()->with('status', 'Variation Deleted Successfully!');
        }

        return redirect()->back()->with('error', 'Something Went Wrong!');

    }

    public function delete_sub_variation($id){
        $sub_variation = SubVariation::findOrFail(base64_decode($id));

        if ($sub_variation->delete()) {
            return redirect()->back()->with('status','Sub Variation Deleted Successfully!!');
        }else{
            return redirect()->back()->with('status','Something went wrong!!');
        }
    }

    public function add_sub_variations($cIndex){
        if($cIndex){
            return '<div class="col-md-3" id="row'.$cIndex.'">
                        <div class="input-group mb-3">
                            <input type="text" name="sub_variations[]" placeholder="eg: Cans, 12Oz Bottles" class="form-control name_list" required/>
                            <div class="input-group-prepend">
                                <button type="button" name="remove" id="'.$cIndex.'" class="btn btn-danger btn_remove">
                                    <i class=" fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>';
        }
    }
}
