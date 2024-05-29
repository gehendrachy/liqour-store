<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Category;
use App\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;


/**
 * Class CategoryController
 * @package App\Http\Controllers\Admin
 */
class CategoryController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function single($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            abort('404');
        }
        $categories = $this->getFullListFromDB($category->id);
        return view('admin.categories', array('categories' => $categories, 'category' => $category, 'id' => '0'));
    }

    /**
     * Category index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
       	$categories = $this->getFullListFromDB();
        return view('admin.categories', array('categories' => $categories, 'id' => '0'));
    }

    /**
     * @param int $parent_id
     * @return \Illuminate\Support\Collection
     */
    public function getFullListFromDB($parent_id = 0)
    {
        $categories = DB::table('categories')->where('parent_id', $parent_id)->orderBy('order_item')->get();

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
        return Category::select('slug')->where('slug', 'like', $slug . '%')
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
     * Create New Category
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function create(Request $request)
    {
     	// dd($request);
        $category = new Category();
        $validateData = $request->validate([
            "title" => 'required|max:255',
        ]);

        $max_order = DB::table('categories')->max('order_item');

        $category->title = $request->title;
        $category->slug = $this->createSlug($request->title);
        $category->order_item = $max_order + 1;

        if ($request->hasFile('image')) {

            //Add the new photo
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $folderPath = "public/categories/";
            $thumbPath = "public/categories/thumbs";

            if (!file_exists($thumbPath)) {
                Storage::makeDirectory($thumbPath, 0777, true, true);
            }

            Storage::putFileAs($folderPath, new File($image), $filename);

            $this->resize_crop_images(1350, 1100, $image, $folderPath . "/thumbs/large_" . $filename);
            $this->resize_crop_images(1350, 550, $image, $folderPath . "/thumbs/medium_" . $filename);
            $this->resize_crop_images(800, 500, $image, $folderPath . "/thumbs/small_" . $filename);

            //Update the database
            $category->image = $filename;

        }

        if (!$request['display']) {
            $request['display'] = 0;
        }

        if (!$request['category']) {
            $request['category'] = 0;
        }
        $category->category = $request['category'];
        $category->display = $request['display'];

        if ($request->parent_id) {
            $category->parent_id = $request->parent_id;
        } else {
            $category->parent_id = 0;
        }

        $category->child = 0;
        $category->content = $request->content;

        $category->save();

        return redirect()->to('admin/categories')->with('status', 'Category Added Successfully!');
    }

    /**
     * Edit Category
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $category = Category::find(base64_decode($id));
        return view('admin.categories', array('category' => $category, 'id' => base64_decode($id)));
    }

    /**
     * Update Category
     *
     * @param Request $request
     */
    public function update(Request $request)
    {
       // dd($request);
        $category = Category::findOrFail($request->id);

        $validateData = $request->validate([
            "title" => 'required|max:255',
        ]);

        // for slug

        $slug = str_slug($request['title'], '-');

    	if ($category->slug != $slug) {
			$category->slug = $this->createSlug($slug, $request['id']);
		}

        $path = public_path() . '/storage/categories/';
        $folderPath = 'public/categories/';

        if (!file_exists($path)) {

            Storage::makeDirectory($folderPath, 0777, true, true);

            if (!is_dir($path . "/thumbs")) {
                Storage::makeDirectory($folderPath . '/thumbs', 0777, true, true);
            }
        }

        $category->title = $request->title;

        if (!$request['display']) {
            $request['display'] = 0;
        }

        if (!$request['category']) {
            $request['category'] = 0;
        }
        $category->category = $request->category;
        $category->display = $request->display;

        $category->content = $request->content;

        if ($request->hasFile('image')) {
            //Add the new photo
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $data = getimagesize($image);
            $folderPath = "public/categories/";
            $thumbPath = "public/categories/thumbs";

            Storage::putFileAs($folderPath, new File($image), $filename);

            $this->resize_crop_images(1350, 1100, $image, $folderPath . "/thumbs/large_" . $filename);
            $this->resize_crop_images(1350, 550, $image, $folderPath . "/thumbs/medium_" . $filename);
            $this->resize_crop_images(800, 500, $image, $folderPath . "/thumbs/small_" . $filename);

            $OldFilename = $category->image;

            //Update the database
            $category->image = $filename;


            //Delete the old photo
            Storage::delete($folderPath . "/" . $OldFilename);
            Storage::delete($folderPath . "/thumbs/large_" . $OldFilename);
            Storage::delete($folderPath . "/thumbs/medium_" . $OldFilename);
            Storage::delete($folderPath . "/thumbs/small_" . $OldFilename);
        }

        $category->save();
        return redirect()->to('admin/categories')->with('status', 'Category Updated Successfully!');;

    }

    /**
     * Delete Category
     *
     * @param $id
     */

    public function delete($id)
    {
        if ($id <= 4 ) {
            return redirect()->back()->with('parent_status' , array('type' => 'danger', 'primary' => 'Sorry!', 'secondary' => 'It cannot be deleted.'));
        }
        $category = Category::where('id' , $id)->firstOrFail();

        if ($category->child == 1) {
        	return redirect()->back()->with('parent_status' , array('type' => 'danger', 'primary' => 'Sorry, Category has Child!', 'secondary' => 'Currently, It cannot be deleted.'));
        }

        $parentId = $category->parent_id;
        $oldImage = $category->image;
        if ($category) {
            if (count($category->product) > 0) {

                return redirect()->back()->with('parent_status' , array('type' => 'danger', 'primary' => 'Sorry, Category has Products!', 'secondary' => 'Currently, It cannot be deleted.'));
                exit();
            }

            if ($category->delete()) {

            	$folderPath = "public/categories";

	            Storage::delete($folderPath ."/".$oldImage);
	            Storage::delete($folderPath ."/thumbs/large_".$oldImage);
	            Storage::delete($folderPath ."/thumbs/small_".$oldImage);
	            Storage::delete($folderPath ."/thumbs/medium_".$oldImage);

	            $childCheck = Category::where('parent_id' , $parentId)->doesntExist();

	            if ($childCheck) {
	            	$updateData = array("child" => 0);
	            	Category::where('id', $parentId)->update($updateData);
	            }
            }

            return redirect()->back()->with('status', 'Category Deleted Successfully!');
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

        $categories = new Category();
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
            Category::where('id', $item['id'])->update($updateData);

            if (array_key_exists("children", $item)) {
                $updateData = array("child" => 1);
                Category::where('id', $item['id'])->update($updateData);
                $this->saveList($item["children"], $item['id'], 0, $m_order);
            }
        }
    }
}
