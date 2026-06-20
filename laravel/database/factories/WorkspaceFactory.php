<?php

namespace Database\Factories;

use App\Models\Workspace;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Workspace>
 */
class WorkspaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
	    'owner_id' => User::factory(),
        ];
    }

    public function forUser(User $user): static
    {
    	return $this->state(fn () => [
        	'owner_id' => $user->id,
    	]);
    }
}
