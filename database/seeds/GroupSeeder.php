<?php

use Illuminate\Database\Seeder;

use Coverfly\Models\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::create ([
            'name' => 'Coverfly',
            'description' => 'Internal testing group for Coverfly people, and Alex',
            'created_by' => 3,
        ]);

        Group::create ([
            'name' => 'CAA',
            'description' => 'Creative Artists Internal script review group',
            'created_by' => 2,
        ]);
    }
}
