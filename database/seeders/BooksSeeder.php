<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Books;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            Book::create([
                'name' => $faker->sentence,
                'author' => $faker->name,
                'publish_date' => $faker->date
            ]);
        }
    }
}
