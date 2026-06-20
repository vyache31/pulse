<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workspace;
use App\Enums\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@example.com',
	    'role' => Role::ADMIN,
	    'password' => 'password',
        ]);

	Workspace::factory()->forUser($user)->create([
	    'title' => 'My workspace'
	]);
    }
}
