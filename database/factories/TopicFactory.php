<?php

namespace Database\Factories;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TopicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Topic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type_id' => 1,
            // 'user_id' => $this->faker->randomDigitNotNull(),
            'user_id' => 1,
            'category_id' => 1,
            'title' => $this->faker->sentence(),
            'body' => '<p>'.$this->faker->paragraph().'</p>',
            // 'tags' => $this->faker->word().','.$this->faker->word().','.$this->faker->word(),
        ];
    }
}
