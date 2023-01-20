<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Permission::create([
          'role_id'=>1,
          'privacy'=>'ARTICLE',
          'capabilities'=> 'update'
      ]);
        Permission::create([
            'role_id'=>1,
            'privacy'=>'ARTICLE',
            'capabilities'=>'create'
        ]);
    }
}
