<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'          => 'Admin',
            'email'         => 'deliveryAdmin@gmail.com',
            'phone_number'  => '0123456789',
            'password'      => bcrypt('12345678'),
            'is_admin'      => 1,
            'is_active'     => 1,
        ]);

    }
}
