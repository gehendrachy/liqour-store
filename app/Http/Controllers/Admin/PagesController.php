<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Pages;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;


/**
 * Class PagesController
 * @package App\Http\Controllers\Admin
 */
class PagesController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function single($slug)
    {
        $page = Pages::where('slug', $slug)->first();
        if (!$page) {
            abort('404');
        }
        $pages = $this->getFullListFromDB($page->id);
        return view('admin.pages', array('pages' => $pages, 'page' => $page, 'id' => '0'));
    }

    /**
     * Page index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
       	$pages = $this->getFullListFromDB();
        return view('admin.pages', array('pages' => $pages, 'id' => '0'));
    }

    /**
     * @param int $parent_id
     * @return \Illuminate\Support\Collection
     */
    public function getFullListFromDB($parent_id = 0)
    {
        $pages = DB::table('pages')->where('parent_id', $parent_id)->orderBy('order_item')->get();

        foreach ($pages as &$value) {
            $subresult = $this->getFullListFromDB($value->id);

            if (count($subresult) > 0) {
                $value->children = $subresult;
            }
        }

        unset($value);

        return $pages;
    }

    // Slug check and create starts

    /**
     * @param $title
     * @param int $id
     * @return string
     * @throws \Exception
     */
    public function createSlug($title, $id = 0)
    {
        // Normalize the title
        $slug = str_slug($title);

        $allSlugs = $this->getRelatedSlugs($slug, $id);

        // If we haven't used it before then we are all good.
        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        throw new \Exception('Can not create a unique slug');
    }

    /**
     * @param $slug
     * @param int $id
     * @return mixed
     */
    protected function getRelatedSlugs($slug, $id = 0)
    {
        return Pages::select('slug')->where('slug', 'like', $slug . '%')
            ->where('id', '<>', $id)
            ->get();
    }

    /**
     * resize crop image
     *
     * @param $max_width
     * @param $max_height
     * @param $image
     * @param $filename
     */
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


    /**
     * Create New Page
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function create(Request $request)
    {
       
        $page = new Pages();
        $validateData = $request->validate([
            "title" => 'required|max:255',
        ]);

        $max_order = DB::table('pages')->max('order_item');

        $page->title = $request->title;
        $page->slug = $this->createSlug($request->title);
        $page->order_item = $max_order + 1;
        if ($request->subtitles) {
            $page->subtitles = $request['subtitles'];
        }

        if ($request->hasFile('image')) {
            //Add the new photo
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $folderPath = "public/pages/" . $page->slug;
            $thumbPath = "public/pages/" . $page->slug . "/thumbs";

            if (!file_exists($thumbPath)) {
                Storage::makeDirectory($thumbPath, 0777, true, true);
            }

            Storage::putFileAs($folderPath, new File($image), $filename);

            $this->resize_crop_images(800, 1200, $image, $folderPath . "/thumbs/large_" . $filename);
            $this->resize_crop_images(600, 900, $image, $folderPath . "/thumbs/thumb_" . $filename);
            $this->resize_crop_images(200, 300, $image, $folderPath . "/thumbs/portrait_" . $filename);

            //Update the database
            $page->image = $filename;

        }

        if (!$request['display']) {
            $request['display'] = 0;
        }
        if (!$request['featured']) {
            $request['featured'] = 0;
        }

        $page->featured = $request['featured'];
        $page->display = $request['display'];
        if ($request->parent_id) {
            $page->parent_id = $request->parent_id;
        } else {
            $page->parent_id = 0;
        }

        $page->child = 0;
        $page->content = $request->content;

        $page->save();

        return redirect()->back()->with('status', 'Content added Successfully!');
    }

    /**
     * Edit Page
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $page = Pages::find(base64_decode($id));
        return view('admin.pages', array('page' => $page, 'id' => base64_decode($id)));
    }

    /**
     * Update Page
     *
     * @param Request $request
     */
    public function update(Request $request)
    {
       // dd($request);
        $page = Pages::findOrFail($request->id);

        $validateData = $request->validate([
            "title" => 'required|max:255',
        ]);

        // for slug

        $slug = $this->createSlug($request->title, $request->id);

        $path = public_path() . '/storage/pages/' . $page->slug;
        if ($page->slug != $slug) {
            $oldslug = $page->slug;
            // $project->slug = $this->createSlug($slug, $request['id']);
            $page->slug = $this->createSlug($slug, $request->id);

            $slug = $page->slug;
            if (file_exists($path)) {
                Storage::move('public/pages/' . $oldslug, 'public/pages/' . $slug);
            }
        }
        $folderPath = 'public/pages/' . $slug;


        if (!file_exists($path)) {

            Storage::makeDirectory($folderPath, 0777, true, true);

            if (!is_dir($path . "/thumbs")) {
                Storage::makeDirectory($folderPath . '/thumbs', 0777, true, true);
            }
        }

        $page->title = $request->title;
        $page->subtitles = $request->subtitles;
        if ($request->featured) {
            $page->featured = $request->featured;
        }
        if ($request->display) {
            $page->display = $request->display;
        }

        $page->content = $request->content;

        if ($request->hasFile('image')) {
            //Add the new photo
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $data = getimagesize($image);
            $folderPath = "public/pages/" . $slug;
            $thumbPath = "public/pages/" . $slug . "/thumbs";

            Storage::putFileAs($folderPath, new File($image), $filename);

            $this->resize_crop_images(800, 1200, $image, $folderPath . "/thumbs/large_" . $filename);
            $this->resize_crop_images(600, 900, $image, $folderPath . "/thumbs/thumb_" . $filename);
            $this->resize_crop_images(200, 300, $image, $folderPath . "/thumbs/portrait_" . $filename);

            $OldFilename = $page->image;

            //Update the database
            $page->image = $filename;


            //Delete the old photo
            Storage::delete($folderPath . "/" . $OldFilename);
            Storage::delete($folderPath . "/thumbs/large_" . $OldFilename);
            Storage::delete($folderPath . "/thumbs/thumb_" . $OldFilename);
            Storage::delete($folderPath . "/thumbs/portrait_" . $OldFilename);
        }

        $page->save();
        return redirect()->to('admin/pages')->with('status', 'Content updated Successfully!');;


    }

    /**
     * Delete Page
     *
     * @param $id
     */
    
    public function delete($id)
    {
        $page = Pages::where('id' , $id)->firstOrFail();

        if ($page->child == 1) {
            return redirect()->back()->with('parent_status' , array('type' => 'danger', 'primary' => 'Sorry, Page has Child!', 'secondary' => 'Currently, It cannot be deleted.'));         
        }

        $parentId = $page->parent_id;

        if ($page) {

            if ($page->delete()) {
                
                $folderPath = 'public/pages/' . $page->slug;
                Storage::deleteDirectory($folderPath);

                $childCheck = Pages::where('parent_id' , $parentId)->doesntExist();

                if ($childCheck) {
                    $updateData = array("child" => 0);
                    Pages::where('id', $parentId)->update($updateData);
                }
            }
            return redirect()->back()->with('status', 'Content Deleted Successfully!!');
        }

        return redirect()->back()->with('error', 'Something Went Wrong!');
    }


    /**
     * Upload Image on Server from summernote File Uploader
     *
     * @param Request $request
     */
    public function imageupload(Request $request)
    {
        if ($request->hasFile('file')) {
            //Add the new photo
            $image = $request->file('file');

            $filename = time() . '.' . $image->getClientOriginalExtension();
            $folderPath = "public/summernote/";

        }


        if ($_FILES['file']['name']) {
            if (!$_FILES['file']['error']) {
                $name = md5(rand(100, 200));
                $ext = explode('.', $_FILES['file']['name']);
                $filename = $name . '.' . $ext[1];
                $destination = $folderPath; //change this directory
                $location = $_FILES["file"]["tmp_name"];
                move_uploaded_file($location, $destination);
                echo $folderPath . '/' . $filename;//change this URL
            } else {
                echo $message = 'Ooops!  Your upload triggered the following error:  ' . $_FILES['file']['error'];
            }
        }
        $data = array('url' => $destination);
    }


    /**
     * @param Request $request
     */
    public function set_order(Request $request)
    {

        $pages = new Pages();
        $list_order = $request['list_order'];

        $this->saveList($list_order, $request->parent_id);
        $data = array('status' => 'success');
        echo json_encode($data);
        exit;
    }

    /**
     * @param $list
     * @param int $parent_id
     * @param int $child
     * @param int $m_order
     */
    function saveList($list, $parent_id = 0, $child = 0, &$m_order = 0)
    {

        foreach ($list as $item) {
            $m_order++;
            $updateData = array("parent_id" => $parent_id, "child" => $child, "order_item" => $m_order);
            Pages::where('id', $item['id'])->update($updateData);

            if (array_key_exists("children", $item)) {
                $updateData = array("child" => 1);
                Pages::where('id', $item['id'])->update($updateData);
                $this->saveList($item["children"], $item['id'], 0, $m_order);
            }
        }
    }
}
