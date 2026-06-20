<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use App\Enums\UserRole;
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
	    'role' => UserRole::ADMIN,
	    'password' => 'password',
        ]);

	$workspace = Workspace::factory()->forUser($user)->create([
	    'name' => 'My workspace'
	]);

	WorkspaceMember::factory()->forOwnerUser($user)->forWorkspace($workspace)->create();
    }
}
