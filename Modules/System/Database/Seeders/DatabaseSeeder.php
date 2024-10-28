<?php

namespace Modules\System\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ModuleSystemAttachmentsSeeder::class);
        $this->call(ModuleSystemSettingsSeeder::class);
    }
}
