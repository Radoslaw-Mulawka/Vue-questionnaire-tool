<?php

use App\Laravue\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'firstName' => 'Admin',
            'lastName' => 'Admin',
            'accept_terms' => 1,
            'verificationHash' => 'sadsadasdasdasdsa',
            'email' => 'admin@tell-it.us',
                'password' => Hash::make('laravue'),
        ]);
//        $manager = User::create([
//            'firstName' => 'Manager',
//            'lastName' => 'Manager',
//            'accept_terms' => 1,
//            'verificationHash' => 'sadsadasdasdasdsa',
//            'email' => 'manager@tell-it.us',
//            'password' => Hash::make('laravue'),
//        ]);
//        $editor = User::create([
//            'firstName' => 'Editor',
//            'lastName' => 'Editor',
//            'accept_terms' => 1,
//            'verificationHash' => 'sadsadasdasdasdsa',
//            'email' => 'editor@tell-it.us',
//            'password' => Hash::make('laravue'),
//        ]);
        $user = User::create([
            'firstName' => 'User',
            'lastName' => 'User',
            'accept_terms' => 1,
            'verificationHash' => 'sadsadasdasdasdsa',
            'email' => 'user@tell-it.us',
            'password' => Hash::make('laravue'),
        ]);
//        $visitor = User::create([
//            'firstName' => 'Visitor',
//            'lastName' => 'Visitor',
//            'accept_terms' => 1,
//            'verificationHash' => 'sadsadasdasdasdsa',
//            'email' => 'visitor@tell-it.us',
//            'password' => Hash::make('laravue'),
//        ]);

        $adminRole = Role::findByName(\App\Laravue\Acl::ROLE_ADMIN);
//        $managerRole = Role::findByName(\App\Laravue\Acl::ROLE_MANAGER);
//        $editorRole = Role::findByName(\App\Laravue\Acl::ROLE_EDITOR);
        $userRole = Role::findByName(\App\Laravue\Acl::ROLE_USER);
//        $visitorRole = Role::findByName(\App\Laravue\Acl::ROLE_VISITOR);
        $admin->syncRoles($adminRole);
//        $manager->syncRoles($managerRole);
//        $editor->syncRoles($editorRole);
        $user->syncRoles($userRole);
//        $visitor->syncRoles($visitorRole);
//        $this->call(UsersTableSeeder::class);
    }
}
