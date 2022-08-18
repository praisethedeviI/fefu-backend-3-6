<?php

namespace Database\Seeders;

use App\Models\Settings;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::query()->truncate();
        $now = Carbon::now();
        DB::table('settings')->insert([
            'external_sync_url' => 'https://raw.githubusercontent.com/chrn-feip/merch_shop_external/main/api/sync',
            'external_sync_products_update_token' => 0,
            'admin_email' => env('ADMIN_EMAIL'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
