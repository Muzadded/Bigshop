<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run():void
    {
        DB::table('admins')->insert(array(
            array(
                'name' => "Alif chad",
                'email' => "alif@mail.com",
                'password' => bcrypt('alif1245'),
            )
        ));
    }
}
