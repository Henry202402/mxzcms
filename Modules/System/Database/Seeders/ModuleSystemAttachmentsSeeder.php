<?php

namespace Modules\System\Database\Seeders;

use Illuminate\Database\Seeder;

class ModuleSystemAttachmentsSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('module_system_attachments')->delete();

        \DB::table('module_system_attachments')->insert(array (
  0 => 
  array(
     'path' => 'avatar/avatar.jpg',
     'path_md5' => '5bcd1045b3066ad8d6b58e188d66d49f',
     'drive' => 'local',
     'create_at' => '2023-07-26 03:26:49',
     'update_at' => '2023-07-26 03:26:49',
  ),
  1 => 
  array(
     'path' => 'website/logo.png',
     'path_md5' => '45015146e362f86bb1f7d9721a22c192',
     'drive' => 'local',
     'create_at' => '2023-07-26 03:26:49',
     'update_at' => '2024-03-05 14:59:50',
  ),
  2 => 
  array(
     'path' => 'website/webicon.ico',
     'path_md5' => '769a5be2a7062d89772f608fff9e8a60',
     'drive' => 'local',
     'create_at' => '2023-07-26 03:26:49',
     'update_at' => '2024-03-05 14:59:07',
  ),
  3 => 
  array(
     'path' => 'website/member_logo.png',
     'path_md5' => 'a3e71be8cc26a4a91b813da9e5252f25',
     'drive' => 'local',
     'create_at' => '2025-03-21 15:11:20',
     'update_at' => '2025-03-21 15:11:20',
  ),
));


    }
}
