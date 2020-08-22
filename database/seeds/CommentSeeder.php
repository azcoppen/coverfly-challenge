<?php

use Illuminate\Database\Seeder;

use Coverfly\Models\Project;
use Coverfly\Models\User;

class CommentSeeder extends Seeder
{

    private function generate ()
    {
        $project = Project::first();

        $users = User::inRandomOrder()->get();

        $faker = Faker\Factory::create();

        foreach ($users AS $user)
        {
            $user
                ->comment ($project, \Crypt::encrypt ($faker->sentence()), rand (1,5))
                ->approve();
        }
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i <= 3; $i++)
        {
            $this->generate();
        }
    }
}
