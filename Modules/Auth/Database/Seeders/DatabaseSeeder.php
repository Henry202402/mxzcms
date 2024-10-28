<?php

namespace Modules\Auth\Database\Seeders;

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
        $this->call(ModuleAuthGroupSeeder::class);
        $this->call(ModuleAuthGroupUserSeeder::class);
    }
}
