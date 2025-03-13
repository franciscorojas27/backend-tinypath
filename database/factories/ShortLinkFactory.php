<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShortLink>
 */
class ShortLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'short_link' => $this->faker->unique()->regexify('[A-Za-z0-9]{5}'),
            'expire_at' => now()->addDays(7),
            'original_link' => $this->faker->url(),
        ];
    }
}
