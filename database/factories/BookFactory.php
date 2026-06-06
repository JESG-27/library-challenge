<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition():array{
        return [
            'name' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'publication_date' => $this->faker->date(),
            'is_available' => $this->faker->boolean(80),
            'user_id' => null,
        ];
    }
}
