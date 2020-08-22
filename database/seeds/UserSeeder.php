<?php

use Illuminate\Database\Seeder;

use Coverfly\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        User::create ([
            'first_name'    => 'Alex',
            'last_name'     => 'Cameron',
            'vanity'        => 'alex',
            'email'         => 'ac@coverfly.com',
            'password'      => Hash::make ('bundy'),
            'otp_secret'    => Str::random (24),
            'remember_token'=> Str::random(10),
            'created_by'    => 1,
        ]);

        User::create ([
            'first_name'    => 'Scot',
            'last_name'     => 'Lawrie',
            'vanity'        => 'scot',
            'email'         => 'scot@coverfly.com',
            'password'      => Hash::make ('bundy'),
            'otp_secret'    => Str::random (24),
            'remember_token' => Str::random(10),
            'created_by'    => 1,
        ]);

        User::create ([
            'first_name'    => 'Mitch',
            'last_name'     => 'Lusas',
            'vanity'        => 'mitch',
            'email'         => 'mitch@coverfly.com',
            'password'      => Hash::make ('bundy'),
            'otp_secret'    => Str::random (24),
            'remember_token'=> Str::random(10),
            'created_by'    => 2,
        ]);

        // Create 10 other users
        factory(User::class, 10)->create();

        // create 10 CAA users
        factory(User::class, 10)->make()->each(function ($user) {
            $user->email = $user->first_name.'.'.$user->last_name.'@caa.com';
            return $user->save();
        });
    }
}
