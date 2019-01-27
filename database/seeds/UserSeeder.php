<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder {

    public function run()
    {
        User::truncate();

        factory(User::class)
            ->create([
                'name' => 'John Doe',
                'email' => 'john@example.com'
            ]);
    }

}