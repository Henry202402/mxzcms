<?php

namespace Modules\Member\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->call(ModuleMemberBaseConfigurationSeeder::class);
    }
}
