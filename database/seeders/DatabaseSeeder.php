<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bookstore.test'],
            [
                'name' => 'Bookstore Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
        );

        User::updateOrCreate(
            ['email' => 'customer@bookstore.test'],
            [
                'name' => 'Sample Customer',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
        );

        $categories = collect([
            ['name' => 'Fiction', 'description' => 'Modern novels, page-turners, and literary favorites.'],
            ['name' => 'Business', 'description' => 'Strategy, leadership, growth, and decision-making.'],
            ['name' => 'Technology', 'description' => 'Software, AI, architecture, and engineering craft.'],
            ['name' => 'Self Development', 'description' => 'Habits, mindset, productivity, and personal growth.'],
        ])->mapWithKeys(function (array $category): array {
            $model = Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'image' => null,
                ],
            );

            return [$model->slug => $model];
        });

        $authors = collect([
            ['name' => 'James Clear', 'born_year' => 1986, 'bio' => 'Author focused on habits, systems, and long-term performance.'],
            ['name' => 'Cal Newport', 'born_year' => 1982, 'bio' => 'Writer and professor known for deep work and digital minimalism.'],
            ['name' => 'Robert C. Martin', 'born_year' => 1952, 'bio' => 'Software craftsman and author on clean code and architecture.'],
            ['name' => 'Morgan Housel', 'born_year' => 1984, 'bio' => 'Financial storyteller exploring behavior, risk, and decision-making.'],
        ])->mapWithKeys(function (array $author): array {
            $model = Author::updateOrCreate(
                ['name' => $author['name']],
                [
                    'bio' => $author['bio'],
                    'born_year' => $author['born_year'],
                    'photo' => null,
                ],
            );

            return [$model->name => $model];
        });

        collect([
            [
                'title' => 'Atomic Habits',
                'description' => 'A practical guide to building good habits and breaking bad ones with tiny, repeatable changes.',
                'price' => 149000,
                'discount_price' => 119000,
                'stock' => 25,
                'published_at' => '2018-10-16',
                'rating' => 4.8,
                'category' => 'Self Development',
                'author' => 'James Clear',
            ],
            [
                'title' => 'Deep Work',
                'description' => 'A focused argument for concentrated effort in a distracted world.',
                'price' => 139000,
                'discount_price' => 115000,
                'stock' => 18,
                'published_at' => '2016-01-05',
                'rating' => 4.6,
                'category' => 'Self Development',
                'author' => 'Cal Newport',
            ],
            [
                'title' => 'Clean Architecture',
                'description' => 'A practical handbook for structuring maintainable software systems.',
                'price' => 229000,
                'discount_price' => 199000,
                'stock' => 14,
                'published_at' => '2017-09-20',
                'rating' => 4.7,
                'category' => 'Technology',
                'author' => 'Robert C. Martin',
            ],
            [
                'title' => 'The Psychology of Money',
                'description' => 'Stories about behavior, wealth, and why good decisions are not always about data.',
                'price' => 159000,
                'discount_price' => null,
                'stock' => 21,
                'published_at' => '2020-09-08',
                'rating' => 4.7,
                'category' => 'Business',
                'author' => 'Morgan Housel',
            ],
            [
                'title' => 'Digital Minimalism',
                'description' => 'An intentional strategy for reclaiming attention from noisy technology.',
                'price' => 169000,
                'discount_price' => 145000,
                'stock' => 11,
                'published_at' => '2019-02-05',
                'rating' => 4.5,
                'category' => 'Technology',
                'author' => 'Cal Newport',
            ],
            [
                'title' => 'The Midnight Library',
                'description' => 'A reflective novel about alternate lives, regret, and second chances.',
                'price' => 129000,
                'discount_price' => 109000,
                'stock' => 16,
                'published_at' => '2020-08-13',
                'rating' => 4.4,
                'category' => 'Fiction',
                'author' => 'Morgan Housel',
            ],
        ])->each(function (array $book) use ($authors, $categories): void {
            Book::updateOrCreate(
                ['slug' => Str::slug($book['title'])],
                [
                    'title' => $book['title'],
                    'description' => $book['description'],
                    'price' => $book['price'],
                    'discount_price' => $book['discount_price'],
                    'stock' => $book['stock'],
                    'cover_image' => null,
                    'published_at' => $book['published_at'],
                    'rating' => $book['rating'],
                    'author_id' => $authors[$book['author']]->id,
                    'category_id' => $categories[Str::slug($book['category'])]->id,
                ],
            );
        });
    }
}
