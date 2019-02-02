<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::truncate();

        factory(User::class)
            ->create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]);
    }
}
