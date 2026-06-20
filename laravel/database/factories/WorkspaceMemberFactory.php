<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Workspace;
use App\Enums\WorkspaceMemberRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class WorkspaceMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workspace_id' => Workspace::factory(),
	    'user_id' => User::factory(),
    	    'role' => WorkspaceMemberRole::MEMBER
        ];
    }

    public function forUser(User $user): static
    {
	return $this->state(fn () => [
		'user_id' => $user->id,
	]);
    }


    public function forOwnerUser(User $user): static
    {
	return $this->state(fn () => [
		'user_id' => $user->id,
		'role' => WorkspaceMemberRole::OWNER,
	]);
    }

    public function forWorkspace(Workspace $workspace): static
    {
	return $this->state(fn () => [
		'workspace_id' => $workspace->id,
	]);
    }
}
