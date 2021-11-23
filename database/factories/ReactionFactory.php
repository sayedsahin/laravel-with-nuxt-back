<?php

namespace Database\Factories;

use App\Models\Reaction;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $types = ['like', 'dislike', 'love'];
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'reactable_type' => 'App\Models\Reply',
            'reactable_id' => Topic::inRandomOrder()->first()->id,
            'type' => $types[array_rand($types)],
        ];
    }
}
