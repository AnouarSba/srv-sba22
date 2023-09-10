<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'nouari aissa',
            'email' =>'nouariaissa44@gmail.com',
            'password' => Hash::make('123123123'),
        ]);
       $role = Role::create(['name' => 'super_admin']);
       $user = User::find(1);
       $user->assignRole('super_admin');
    }
}
