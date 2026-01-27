<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        Setting::updateOrCreate(['key' => 'site_name'], ['value' => 'Lebanese Marketplace']);
        Setting::updateOrCreate(['key' => 'tagline'], ['value' => 'Fresh from the farm to your table']);
        Setting::updateOrCreate(['key' => 'platform_fee'], ['value' => '6']); // 6% Fee
    }
}
