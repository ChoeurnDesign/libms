<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Book;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Seed required data for the welcome page
        $category = Category::factory()->create([
            'name' => 'Fiction',
            'description' => 'Fictional literature and novels',
        ]);

        Book::factory()->create([
            'title' => 'The Kite Runner',
            'slug' => 'the-kite-runner', // <-- add this line
            'author' => 'Khaled Hosseini',
            'isbn' => '9781594631931',
            'category_id' => $category->id,
            'description' => 'A story of friendship, betrayal, and redemption set against the backdrop of Afghanistan.',
            'quantity' => 6,
            'available_quantity' => 5,
            'location' => 'Section B - Shelf 1',
            'cover_image' => 'book_covers/fiction (1).jpg',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
