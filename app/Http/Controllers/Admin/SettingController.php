<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Setting;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::find(1);
        return view('admin.setting', compact('setting'));
    }

    public function resize_crop_images($max_width, $max_height, $image, $filename)
    {
        $imgSize = getimagesize($image);
        $width = $imgSize[0];
        $height = $imgSize[1];

        $width_new = round($height * $max_width / $max_height);
        $height_new = round($width * $max_height / $max_width);

        if ($width_new > $width) {
            //cut point by height
            $h_point = round(($height - $height_new) / 2);

            $cover = storage_path('app/' . $filename);
            Image::make($image)->crop($width, $height_new, 0, $h_point)->resize($max_width, $max_height)->save($cover);
        } else {
            //cut point by width
            $w_point = round(($width - $width_new) / 2);
            $cover = storage_path('app/' . $filename);
            Image::make($image)->crop($width_new, $height, $w_point, 0)->resize($max_width, $max_height)->save($cover);
        }

    }

    public function update(Request $request)
    {
       // dd($request);
        $setting = Setting::find('1');
        $validatedData = $request->validate([
            'sitetitle' => 'required|max:255',
            'siteemail' => 'required|max:225|email',
        ]);
        $setting->sitetitle = $request->sitetitle;
        $setting->siteemail = $request['siteemail'];
        $setting->phone = $request['phone'];
        $setting->mobile = $request['mobile'];
        $setting->fax = $request['fax'];
        $setting->address = $request['address'];
        $setting->facebookurl = $request['facebookurl'];
        $setting->twitterurl = $request['twitterurl'];
        $setting->instagramurl = $request['instagramurl'];
        $setting->youtubeurl = $request['youtubeurl'];
        $setting->sitekeyword = $request['sitekeyword'];
        $setting->googlemapurl = $request['googlemapurl'];

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $filename = time() . '.' . $logo->getClientOriginalExtension();
            $oldlogo = $setting->logo;
            $validatedData = $request->validate([
                'logo' => 'image|mimes:jpeg,png,jpg|max:1000',
            ]);

            Storage::putFileAs('public/setting/logo', new File($logo), $filename);

            $setting->logo = $filename;

            $this->resize_crop_images(200, 200, $logo, "public/setting/logo/thumb_" . $filename);
            if ($oldlogo != null) {
                //deleting exiting logo
                Storage::delete('public/setting/logo/' . $oldlogo);
                Storage::delete('public/setting/logo/thumb_' . $oldlogo);
            }
        }
        if ($request->hasFile('favicon')) {
            $logo = $request->file('favicon');
            $filename = time() . '.' . $logo->getClientOriginalExtension();
            $oldfavicon = $setting->favicon;
            $validatedData = $request->validate([
                'favicon' => 'image|mimes:jpeg,png,jpg',
            ]);

            Storage::putFileAs('public/setting/favicon', new File($logo), $filename);

            $setting->favicon = $filename;

            $this->resize_crop_images(200, 200, $logo, "public/setting/favicon/thumb_" . $filename);
            if ($oldfavicon != null) {
                //deleting exiting logo
                Storage::delete('public/setting/favicon/' . $oldfavicon);
                Storage::delete('public/setting/favicon/thumb_' . $oldfavicon);
            }
        }
        $setting->save();

        return redirect('admin/setting')->with('status','Setting Update Successfully.');

    }
}
