<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Slider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sliders = DB::table('sliders')->orderBy('order_item')->get();
        return view('admin.sliders', array('sliders' => $sliders, 'id' => '0'));
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

    public function createslide(Request $request)
    {
        $slider = new Slider();
        
        $max_order = DB::table('sliders')->max('order_item');
        $max_order = $max_order == '' ? 0 : $max_order;

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'link' => 'required|max:225',
        ]);

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $validatedData = $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg|max:50000',
            ]);

            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $folderPath = "public/slider";
            $thumbPath = "public/slider/thumbs";
            if(!file_exists($thumbPath)){
                Storage::makeDirectory($thumbPath,0777,true,true);
            }
            Storage::putFileAs($folderPath, new File($image), $filename);
            $this->resize_crop_images(1920, 600, $image, $thumbPath."/slide_". $filename);
            $this->resize_crop_images(384, 120, $image, $thumbPath."/small_". $filename);
            $slider->image = $filename;
        }
        $request->status = isset($request->status) ? $request->status : '0';
        $slider->title = $request->title;
        $slider->subtitles = '';
        $slider->buttonName = '';
        $slider->link = $request->link;
        $slider->status = $request->status;
        $slider->order_item = $max_order + 1;
        $slider->created_by = Auth::user()->name;
        $slider->save();
        return redirect()->to('/admin/sliders')->with('status', 'Slide Added Successfully!');
    }

    public function editslide($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.sliders', array('slider' => $slider, 'id' => $id));
    }

    public function set_order(Request $request){

        $list_order = $request->list_order;

        $i = 1 ;
        foreach($list_order as $id) {
            $updateData = array("order_item" => $i);
            Slider::where('id', $id)->update($updateData);

            $i++ ;
        }
        $data = array('status'=> 'success');
        echo json_encode($data);
    }

    public function updateslide(Request $request)
    {

        $slide = Slider::find($request->id);

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'link' => 'required|max:225',
        ]);

        if (!$request->status) {
            $request->status = 0;
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $validatedData = $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg|max:50000',
            ]);

            $oldslide = $slide->image;

            $filename = time().'.'.$image->getClientOriginalExtension();
            $folderPath = "public/slider";

            Storage::putFileAs($folderPath, new File($image), $filename);

            $this->resize_crop_images(1920, 600, $image, $folderPath."/thumbs/slide_".$filename);
            $this->resize_crop_images(384, 120, $image, $folderPath."/thumbs/small_".$filename);

            Storage::delete($folderPath .'/'. $oldslide);
            Storage::delete($folderPath .'/thumbs/slide_'. $oldslide);
            Storage::delete($folderPath .'/thumbs/small_'. $oldslide);

            $slide->image = $filename;
        }
        $slide->title = $request->title;
        $slide->subtitles = '';
        $slide->buttonName = '';
        $slide->link = $request->link;
        $slide->status = $request->status;
        $slide->updated_by = Auth::user()->name;
        
        $slide->save();
        return redirect()->to('admin/sliders')->with('status', 'Slide Updated Successfully!');

    }

    public function delete($id)
    {
        $slide = Slider::findOrFail($id);

        $oldslide = $slide->image;

        if ($slide) {
            $slide->delete();
            $folderPath = "public/slider";
            Storage::delete($folderPath .'/'. $oldslide);
            Storage::delete($folderPath .'/thumbs/slide_'. $oldslide);
            Storage::delete($folderPath .'/thumbs/small_'. $oldslide);

            return redirect()->to('/admin/sliders')->with('status', 'Slide Deleted Successfully!');
        }
        return redirect()->to('/admin/sliders')->with('error', 'Something Went Wrong!');

    }
}
