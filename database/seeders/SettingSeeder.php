<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'site_name_ar' => 'parcel-web',
            'site_name_en' => 'parcel-web',
            'phone' => '8484858845855',
            'email' => 'info@eggs-plus.com',
            'logo' => 'uploads/setting/login_white.png',
            'login_pg' => 'uploads/setting/login_image.png',
            'logo_login' => 'uploads/setting/login_page_logo.png',
            'location' => null,
            'facebook' => 'https://www.facebook.com',
            'twitter' => null,
            'instagram' => null,
            'pinterest' => null,
            'snapchat' => null,
            'telegram' => null,
            'youtube' => null,
            'address_ar' => 'parcel-web parcel-web',
            'address_en' => 'parcel-web parcel-web',
            'sm_description_ar' => 'parcel-web description about application',
            'sm_description_en' => 'parcel-web description about application',
            'copyright' => 'جميع الحقوق محفوظة منصة parcel-web، تنفيذ و تطوير بواسطة',
            'copyright_link_text' => 'parcel-web',
            'copyright_link' => 'http://www.google.com',

            'terms_ar' => 'terms_ar',
            'terms_en' => 'terms_en',
            'privacy_ar' => 'privacy_ar',
            'privacy_en' => 'privacy_en',
            'usage_ar' => 'usage_ar',
            'usage_en' => 'usage_en',
            'about_ar' => 'about_ar',
            'about_en' => 'about_en',
            'delivery_cost' => '200',
            'cash_on_delivery' => '0',
        ];


        Setting::setMany($data);
    }
}
