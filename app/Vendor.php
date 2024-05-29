<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class Vendor extends Model
{

	protected $fillable = [
		    'user_id', 'store_name', 'slug', 'image', 'featured', 'display', 'address_1', 'address_2', 'city', 'state', 'zip_code', 'phone', 'contact_name', 'mobile', 'email', 'opening_time', 'closing_time', 'delivery_fee', 'minimum_order', 'tax_rate_1', 'tax_rate_2', 'tax_rate_3', 'bottle_deposit_1_rate', 'bottle_deposit_2_rate', 'commission_percentage', 'description', 'created_by', 'updated_by'
		];

    protected $hidden = [
        'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    // Slug check and create starts
	public static function createSlug($title, $id = 0)
    {
        $slug = str_slug($title);

        $allSlugs = Self::getRelatedSlugs($slug, $id);

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
    
    protected static function getRelatedSlugs($slug, $id = 0)
    {
        return Vendor::select('slug')->where('slug', 'like', $slug.'%')
            ->where('user_id', '<>', $id)
            ->get();
    }

    public static function resize_crop_images($max_width, $max_height, $image, $filename){
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
}
