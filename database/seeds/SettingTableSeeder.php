<?php

use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $current_time = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        DB::table('settings')->insert([
            'logo' => 'logo.png',
            'favicon' => 'favicon.png',
            'sitetitle' => 'My Site',
            'siteemail' => 'info@yoursite.com',
            'sitekeyword' => 'About Your Site',
            'facebookurl' => 'https://www.facebook.com/',
            'youtubeurl' => 'https://twitter.com/',
            'twitterurl' => 'https://www.linkedin.com/',
            'instagramurl' => 'https://www.instagram.com/',
            'phone' => '9800000000',
            'mobile' => '9800000000',
            'fax' => '4422',
            'address' => 'Kathmandu, Nepal',
            'googlemapurl' => 'google_map_url',
            'created_at' => $current_time,
            'updated_at' => $current_time
        ]);
    }
}
