<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory(10)->create();
        $categories = Category::factory(5)->create();

        Book::factory(30)->create()->each(function ($book) use ($categories, $users) {
            $book->categories()->attach($categories->random(rand(1, 3))->pluck('id')->toArray());
            
            if (!$book->is_available) {
                $book->update([
                    'user_id' => $users->random()->id
                ]);
            }
        });
    }
}
