<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Metric>
 */
class MetricFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'short_link_id' => $this->faker->numberBetween(1, 20),
            'ip_user' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'device' => $this->faker->randomElement(['desktop', 'mobile', 'tablet']),
            'referrer' => $this->faker->url,
            'created_at' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
