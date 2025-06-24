<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Use delete() instead of truncate() to avoid foreign key constraint errors
        Category::query()->delete();

        $categories = [
            ['name' => 'Fiction', 'description' => 'Fictional literature and novels'],
            ['name' => 'Non-Fiction', 'description' => 'Non-fictional books and documentaries'],
            ['name' => 'Science', 'description' => 'Scientific research and educational books'],
            ['name' => 'Technology', 'description' => 'Technology and computer science books'],
            ['name' => 'History', 'description' => 'Historical books and biographies'],
        ];

        foreach ($categories as $category) {
            $category['slug'] = Str::slug($category['name']);
            $category['is_active'] = 1;
            $category['color'] = '#007bff';
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
