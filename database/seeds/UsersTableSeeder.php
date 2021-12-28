<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->truncate();
        
        \DB::table('users')->insert([
            'name' => 'Isidora Lazic',
            'email' => 'isidora.lazic@cubes.rs',
            'password' => \Hash::make('cubesphp'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        \DB::table('users')->insert([
            'name' => 'Pera Peric',
            'email' => 'pera.peric@cubes.rs',
            'password' => \Hash::make('cubesphp'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
