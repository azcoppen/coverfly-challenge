<?php

use Illuminate\Database\Seeder;

use Coverfly\Models\Group;
use Coverfly\Models\Membership;
use Coverfly\Models\User;
use Illuminate\Support\Str;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sync up all members of Coverfly
        $coverfly = Group::where ('name', 'LIKE', 'Coverfly')->first();
        $caa = Group::where ('name', 'LIKE', 'CAA')->first();

        $users = User::all();

        // Pick a random CAA person to be the owner
        $caa_owner = User::where ('email', 'LIKE', '%caa.com%')->inRandomOrder()->first();

        foreach ($users AS $user)
        {
            if ( Str::contains ($user->email, 'coverfly.com') )
            {
                Membership::create ([
                    'member_type'   => User::class,
                    'member_id'     => $user->id,
                    'group_id'      => $coverfly->id,
                    'role'          => 'owner',
                    'approved_at'   => now(),
                    'starts_at'     => now(),
                    'expires_at'    => now()->addYears(10),
                    'created_by'    => 1,
                ]);
            }

            if ( Str::contains ($user->email, 'caa.com') )
            {
                Membership::create ([
                    'member_type'   => User::class,
                    'member_id'     => $user->id,
                    'group_id'      => $caa->id,
                    'role'          => $user->id == $caa_owner->id ? 'owner' : 'default',
                    'approved_at'   => now(),
                    'starts_at'     => now(),
                    'expires_at'    => now()->addYears(1),
                    'created_by'    => $caa_owner->id,
                ]);
            }

        }
    }
}
