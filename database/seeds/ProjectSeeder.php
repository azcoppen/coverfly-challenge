<?php

use Illuminate\Database\Seeder;

use Coverfly\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::create ([
            'name' => 'THE GREAT CODE MARATHON',
            'info' => 'Three intrepid explorers embark upon an epic journey to find the solution to a complex problem never solved by anyone before, which leads them down wildly-deviating roads, foolish endeavours, and ridiculous rewrites, as they battle legal action from sensitive Hollywood types bent on destroying their clever ideas.',
            'created_by' => 2
        ]);
    }
}
