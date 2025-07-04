<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Category;

class BookSeeder extends Seeder
{
    public function run(): void
    {

       // Delete all books (safe, since categories are needed for FK)
        DB::table('books')->delete();
        // Optionally reset auto-increment (not required for FK, but keeps IDs neat)
        DB::statement('ALTER TABLE books AUTO_INCREMENT = 1;');

        // Fetch categories by slug
        $categories = [
            'fiction'      => Category::where('slug', 'fiction')->first(),
            'non-fiction'  => Category::where('slug', 'non-fiction')->first(),
            'science'      => Category::where('slug', 'science')->first(),
            'technology'   => Category::where('slug', 'technology')->first(),
            'history'      => Category::where('slug', 'history')->first(),
        ];

        $books = [

            // Fiction Books (category_id: 1)
            [
                'title' => 'The Kite Runner',
                'slug' => 'the-kite-runner',
                'author' => 'Khaled Hosseini',
                'isbn' => '9781594631931',
                'category_slug' => 'fiction',
                'category_id' => 1,
                'description' => 'A story of friendship, betrayal, and redemption set against the backdrop of Afghanistan.',
                'quantity' => 6,
                'available_quantity' => 5,
                'location' => 'Section B - Shelf 1',
                'cover_image' => 'book_covers/fiction (1).jpg',

            ],
            [
                'title' => 'The Alchemist',
                'slug' => 'the-alchemist',
                'author' => 'Paulo Coelho',
                'isbn' => '9780061122415',
                'category_slug' => 'fiction',
                'category_id' => 1,
                'description' => 'A philosophical story about a young shepherd who travels from Spain to Egypt in search of treasure.',
                'quantity' => 8,
                'available_quantity' => 6,
                'location' => 'Section B - Shelf 1',
                'cover_image' => 'book_covers/fiction (2).jpg',

            ],
            [
                'title' => 'Harry Potter and the Philosopher\'s Stone',
                'slug' => 'harry-potter-and-the-philosophers-stone',
                'author' => 'J.K. Rowling',
                'isbn' => '9780747532699',
                'category_slug' => 'fiction',
                'category_id' => 1,
                'description' => 'The first book in the magical Harry Potter series about a young wizard.',
                'quantity' => 10,
                'available_quantity' => 8,
                'location' => 'Section B - Shelf 1',
                'cover_image' => 'book_covers/fiction (3).jpg',

            ],
            [
                'title' => 'The Midnight Library',
                'slug' => 'the-midnight-library',
                'author' => 'Matt Haig',
                'isbn' => '9780525559474',
                'category_slug' => 'fiction',
                'category_id' => 1,
                'description' => 'A novel about infinite possibilities and the lives we might have lived.',
                'quantity' => 7,
                'available_quantity' => 5,
                'location' => 'Section B - Shelf 1',
                'cover_image' => 'book_covers/fiction (4).jpg',

            ],
            [
                'title' => 'Beach Read',
                'slug' => 'beach-read',
                'author' => 'Emily Henry',
                'isbn' => '9780451489999',
                'category_slug' => 'fiction',
                'category_id' => 1,
                'description' => 'A romantic comedy about two rival writers who challenge each other to write outside their comfort zones.',
                'quantity' => 5,
                'available_quantity' => 4,
                'location' => 'Section B - Shelf 1',
                'cover_image' => 'book_covers/fiction (5).jpg',

            ],
            [
                'title' => 'The Da Vinci Code',
                'slug' => 'the-da-vinci-code',
                'author' => 'Dan Brown',
                'isbn' => '9780307474278',
                'category_slug' => 'fiction',
                'category_id' => 1,
                'description' => 'A mystery thriller involving secret societies, ancient mysteries, and religious history.',
                'quantity' => 6,
                'available_quantity' => 4,
                'location' => 'Section B - Shelf 2',
                'cover_image' => 'book_covers/fiction (6).jpg',

            ],
            [
                'title' => 'To Kill a Mockingbird',
                'slug' => 'to-kill-a-mockingbird',
                'author' => 'Harper Lee',
                'isbn' => '9780061120084',
                'category_slug' => 'fiction',
                'category_id' => 1,
                'description' => 'A gripping tale of racial injustice and childhood innocence in the American South.',
                'quantity' => 5,
                'available_quantity' => 4,
                'location' => 'Section B - Shelf 2',
                'cover_image' => 'book_covers/fiction (7).jpg',

            ],
            [
                'title' => 'It Ends with Us',
                'slug' => 'it-ends-with-us',
                'author' => 'Colleen Hoover',
                'isbn' => '9781501110368',
                'category_slug' => 'fiction',
                'category_id' => 1,
                'description' => 'A powerful story about love, resilience, and the courage to break the cycle.',
                'quantity' => 8,
                'available_quantity' => 6,
                'location' => 'Section B - Shelf 2',
                'cover_image' => 'book_covers/fiction (8).jpg',

            ],
            [
                'title' => 'Where the Crawdads Sing',
                'slug' => 'where-the-crawdads-sing',
                'author' => 'Delia Owens',
                'isbn' => '9780735219090',
                'category_slug' => 'fiction',
                'category_id' => 1,
                'description' => 'A coming-of-age mystery about a young woman who raised herself in the marshes of North Carolina.',
                'quantity' => 7,
                'available_quantity' => 5,
                'location' => 'Section B - Shelf 2',
                'cover_image' => 'book_covers/fiction (9).jpg',

            ],
            [
                'title' => '1984',
                'slug' => '1984',
                'author' => 'George Orwell',
                'isbn' => '9780451524935',
                'category_slug' => 'fiction',
                'category_id' => 1,
                'description' => 'A dystopian novel about totalitarianism and surveillance in a future society.',
                'quantity' => 8,
                'available_quantity' => 6,
                'location' => 'Section B - Shelf 2',
                'cover_image' => 'book_covers/fiction (10).jpg',

            ],

            // Non-Fiction Books (category_id: 2)
            [
                'title' => 'My Next Breath',
                'slug' => 'my-next-breath',
                'author' => 'Jeremy Renner',
                'isbn' => '9781982185206',
                'category_slug' => 'non-fiction',
                'category_id' => 2,
                'description' => 'A memoir about overcoming life-threatening challenges and finding strength.',
                'quantity' => 5,
                'available_quantity' => 4,
                'location' => 'Section C - Shelf 1',
                'cover_image' => 'book_covers/nonfiction (1).jpg',

            ],
            [
                'title' => 'The Subtle Art of Not Giving a F*ck',
                'slug' => 'the-subtle-art-of-not-giving-a-fck',
                'author' => 'Mark Manson',
                'isbn' => '9780062457714',
                'category_slug' => 'non-fiction',
                'category_id' => 2,
                'description' => 'A counterintuitive approach to living a good life.',
                'quantity' => 8,
                'available_quantity' => 6,
                'location' => 'Section C - Shelf 1',
                'cover_image' => 'book_covers/nonfiction (2).jpg',

            ],
            [
                'title' => 'Educated',
                'slug' => 'educated',
                'author' => 'Tara Westover',
                'isbn' => '9780399590504',
                'category_slug' => 'non-fiction',
                'category_id' => 2,
                'description' => 'A memoir about education, family, and the struggle between loyalty and self-actualization.',
                'quantity' => 5,
                'available_quantity' => 3,
                'location' => 'Section C - Shelf 1',
                'cover_image' => 'book_covers/nonfiction (3).jpg',

            ],
            [
                'title' => 'Atomic Habits',
                'slug' => 'atomic-habits',
                'author' => 'James Clear',
                'isbn' => '9780735211292',
                'category_slug' => 'non-fiction',
                'category_id' => 2,
                'description' => 'An easy and proven way to build good habits and break bad ones.',
                'quantity' => 9,
                'available_quantity' => 7,
                'location' => 'Section C - Shelf 1',
                'cover_image' => 'book_covers/nonfiction (4).jpg',

            ],
            [
                'title' => 'Untamed',
                'slug' => 'untamed',
                'author' => 'Glennon Doyle',
                'isbn' => '9781984801258',
                'category_slug' => 'non-fiction',
                'category_id' => 2,
                'description' => 'A memoir about breaking free from societal expectations and finding your true self.',
                'quantity' => 6,
                'available_quantity' => 4,
                'location' => 'Section C - Shelf 2',
                'cover_image' => 'book_covers/nonfiction (5).jpg',

            ],
            [
                'title' => 'Think Again',
                'slug' => 'think-again',
                'author' => 'Adam Grant',
                'isbn' => '9781984878106',
                'category_slug' => 'non-fiction',
                'category_id' => 2,
                'description' => 'The power of knowing what you don\'t know.',
                'quantity' => 7,
                'available_quantity' => 5,
                'location' => 'Section C - Shelf 2',
                'cover_image' => 'book_covers/nonfiction (6).jpg',

            ],
            [
                'title' => 'The Wager',
                'slug' => 'the-wager',
                'author' => 'David Grann',
                'isbn' => '9780385534260',
                'category_slug' => 'non-fiction',
                'category_id' => 2,
                'description' => 'A tale of shipwreck, mutiny, and murder.',
                'quantity' => 5,
                'available_quantity' => 4,
                'location' => 'Section C - Shelf 2',
                'cover_image' => 'book_covers/nonfiction (7).jpg',

            ],
            [
                'title' => 'Second Life',
                'slug' => 'second-life',
                'author' => 'Amanda Hess',
                'isbn' => '9780593138445',
                'category_slug' => 'non-fiction',
                'category_id' => 2,
                'description' => 'Being a child in the digital age.',
                'quantity' => 4,
                'available_quantity' => 3,
                'location' => 'Section C - Shelf 2',
                'cover_image' => 'book_covers/nonfiction (8).jpg',

            ],
            [
                'title' => 'Spitfires',
                'slug' => 'spitfires',
                'author' => 'Pegi Aikman',
                'isbn' => '9780802159373',
                'category_slug' => 'non-fiction',
                'category_id' => 2,
                'description' => 'The women who flew for the first time in the face of death.',
                'quantity' => 4,
                'available_quantity' => 3,
                'location' => 'Section C - Shelf 3',
                'cover_image' => 'book_covers/nonfiction (9).jpg',

            ],
            [
                'title' => 'Mark Twain',
                'slug' => 'mark-twain',
                'author' => 'Ron Chernow',
                'isbn' => '9780143111733',
                'category_slug' => 'non-fiction',
                'category_id' => 2,
                'description' => 'A comprehensive biography of America\'s greatest humorist.',
                'quantity' => 3,
                'available_quantity' => 2,
                'location' => 'Section C - Shelf 3',
                'cover_image' => 'book_covers/nonfiction (10).jpg',

            ],

            // Science Fiction Books (category_id: 3)
            [
                'title' => 'Overgrowth',
                'slug' => 'overgrowth',
                'author' => 'Mira Grant',
                'isbn' => '9780316379847',
                'category_slug' => 'science',
                'category_id' => 3,
                'description' => 'A thrilling sci-fi novel about survival in a world overrun by nature.',
                'quantity' => 5,
                'available_quantity' => 4,
                'location' => 'Section A - Shelf 3',
                'cover_image' => 'book_covers/sciencefiction (1).jpg',

            ],
            [
                'title' => 'Awake in the Floating City',
                'slug' => 'awake-in-the-floating-city',
                'author' => 'Susanna Kwan',
                'isbn' => '9781234567890',
                'category_slug' => 'science',
                'category_id' => 3,
                'description' => 'A mesmerizing tale of life in a futuristic floating metropolis.',
                'quantity' => 6,
                'available_quantity' => 5,
                'location' => 'Section A - Shelf 3',
                'cover_image' => 'book_covers/sciencefiction (2).jpg',

            ],
            [
                'title' => 'Esperance',
                'slug' => 'esperance',
                'author' => 'Adam Oyebanji',
                'isbn' => '9780765398765',
                'category_slug' => 'science',
                'category_id' => 3,
                'description' => 'A gripping space opera about hope and survival among the stars.',
                'quantity' => 7,
                'available_quantity' => 6,
                'location' => 'Section A - Shelf 3',
                'cover_image' => 'book_covers/sciencefiction (3).jpg',

            ],
            [
                'title' => 'It Takes a Psychic',
                'slug' => 'it-takes-a-psychic',
                'author' => 'Jayne Ann Krentz',
                'isbn' => '9780593438954',
                'category_slug' => 'science',
                'category_id' => 3,
                'description' => 'A paranormal sci-fi romance with psychic abilities and futuristic elements.',
                'quantity' => 5,
                'available_quantity' => 4,
                'location' => 'Section A - Shelf 3',
                'cover_image' => 'book_covers/sciencefiction (4).jpg',

            ],
            [
                'title' => 'The Book of Records',
                'slug' => 'the-book-of-records',
                'author' => 'Madeleine Thien',
                'isbn' => '9780771079870',
                'category_slug' => 'science',
                'category_id' => 3,
                'description' => 'A thought-provoking novel about memory, technology, and the future of human experience.',
                'quantity' => 4,
                'available_quantity' => 3,
                'location' => 'Section A - Shelf 3',
                'cover_image' => 'book_covers/sciencefiction (5).jpg',

            ],
            [
                'title' => 'Dark Dawn',
                'slug' => 'dark-dawn',
                'author' => 'Seth Ring',
                'isbn' => '9781234567891',
                'category_slug' => 'science',
                'category_id' => 3,
                'description' => 'An epic dark fantasy sci-fi adventure in a world where technology meets magic.',
                'quantity' => 6,
                'available_quantity' => 5,
                'location' => 'Section A - Shelf 3',
                'cover_image' => 'book_covers/sciencefiction (6).jpg',

            ],
            [
                'title' => 'All Super-Heroes Need PR',
                'slug' => 'all-super-heroes-need-pr',
                'author' => 'Elizabeth Stephens',
                'isbn' => '9781234567892',
                'category_slug' => 'science',
                'category_id' => 3,
                'description' => 'A humorous take on superhero culture in a modern sci-fi setting.',
                'quantity' => 5,
                'available_quantity' => 4,
                'location' => 'Section A - Shelf 3',
                'cover_image' => 'book_covers/sciencefiction (7).jpg',

            ],
            [
                'title' => 'He Who Fights Monsters',
                'slug' => 'he-who-fights-monsters',
                'author' => 'Shirtaloon',
                'isbn' => '9781234567893',
                'category_slug' => 'science',
                'category_id' => 3,
                'description' => 'A LitRPG adventure combining science fiction elements with fantasy gaming mechanics.',
                'quantity' => 7,
                'available_quantity' => 6,
                'location' => 'Section A - Shelf 3',
                'cover_image' => 'book_covers/sciencefiction (8).jpg',

            ],

            // Technology Books (category_id: 4)
            [
                'title' => 'The 39 Clues: The Maze of Bones',
                'slug' => 'the-39-clues-the-maze-of-bones',
                'author' => 'Rick Riordan',
                'isbn' => '9780545060394',
                'category_slug' => 'technology',
                'category_id' => 4,
                'description' => 'A thrilling adventure combining technology and mystery in the first book of The 39 Clues series.',
                'quantity' => 6,
                'available_quantity' => 5,
                'location' => 'Section D - Shelf 1',
                'cover_image' => 'book_covers/technology (1).jpg',

            ],
            [
                'title' => 'The 39 Clues: One False Note',
                'slug' => 'the-39-clues-one-false-note',
                'author' => 'Gordon Korman',
                'isbn' => '9780545060417',
                'category_slug' => 'technology',
                'category_id' => 4,
                'description' => 'The second installment in the technology-driven adventure series.',
                'quantity' => 5,
                'available_quantity' => 4,
                'location' => 'Section D - Shelf 1',
                'cover_image' => 'book_covers/technology (2).jpg',

            ],
            [
                'title' => 'The 39 Clues: The Sword Thief',
                'slug' => 'the-39-clues-the-sword-thief',
                'author' => 'Peter Lerangis',
                'isbn' => '9780545060431',
                'category_slug' => 'technology',
                'category_id' => 4,
                'description' => 'Third book in the series featuring high-tech gadgets and digital mysteries.',
                'quantity' => 7,
                'available_quantity' => 6,
                'location' => 'Section D - Shelf 1',
                'cover_image' => 'book_covers/technology (3).jpg',

            ],
            [
                'title' => 'The 39 Clues: Beyond the Grave',
                'slug' => 'the-39-clues-beyond-the-grave',
                'author' => 'Jude Watson',
                'isbn' => '9780545060448',
                'category_slug' => 'technology',
                'category_id' => 4,
                'description' => 'Fourth adventure combining ancient mysteries with modern technology.',
                'quantity' => 6,
                'available_quantity' => 5,
                'location' => 'Section D - Shelf 1',
                'cover_image' => 'book_covers/technology (4).jpg',

            ],
            [
                'title' => 'The 39 Clues: The Black Circle',
                'slug' => 'the-39-clues-the-black-circle',
                'author' => 'Patrick Carman',
                'isbn' => '9780545060462',
                'category_slug' => 'technology',
                'category_id' => 4,
                'description' => 'Fifth book featuring advanced technology and digital clue-hunting.',
                'quantity' => 5,
                'available_quantity' => 4,
                'location' => 'Section D - Shelf 1',
                'cover_image' => 'book_covers/technology (5).jpg',

            ],
            [
                'title' => 'The Book of Why',
                'slug' => 'the-book-of-why',
                'author' => 'Judea Pearl',
                'isbn' => '9780465097609',
                'category_slug' => 'technology',
                'category_id' => 4,
                'description' => 'The new science of cause and effect in artificial intelligence and data science.',
                'quantity' => 4,
                'available_quantity' => 3,
                'location' => 'Section D - Shelf 2',
                'cover_image' => 'book_covers/technology (6).jpg',

            ],
            [
                'title' => 'AI 2041',
                'slug' => 'ai-2041',
                'author' => 'Kai-Fu Lee',
                'isbn' => '9780593238295',
                'category_slug' => 'technology',
                'category_id' => 4,
                'description' => 'Ten visions for our future with artificial intelligence.',
                'quantity' => 6,
                'available_quantity' => 5,
                'location' => 'Section D - Shelf 2',
                'cover_image' => 'book_covers/technology (7).jpg',

            ],
            [
                'title' => 'The Age of AI',
                'slug' => 'the-age-of-ai',
                'author' => 'Henry A. Kissinger',
                'isbn' => '9780316273800',
                'category_slug' => 'technology',
                'category_id' => 4,
                'description' => 'And our human future - exploring the impact of artificial intelligence on society.',
                'quantity' => 5,
                'available_quantity' => 4,
                'location' => 'Section D - Shelf 2',
                'cover_image' => 'book_covers/technology (8).jpg',

            ],
            [
                'title' => 'A Brief History of Intelligence',
                'slug' => 'a-brief-history-of-intelligence',
                'author' => 'Max Bennett',
                'isbn' => '9780063118423',
                'category_slug' => 'technology',
                'category_id' => 4,
                'description' => 'Evolution, AI, and the five breakthroughs that made our brains.',
                'quantity' => 4,
                'available_quantity' => 3,
                'location' => 'Section D - Shelf 2',
                'cover_image' => 'book_covers/technology (9).jpg',

            ],
            [
                'title' => 'AI Doctor',
                'slug' => 'ai-doctor',
                'author' => 'Ronald M. Razmi',
                'isbn' => '9781633699113',
                'category_slug' => 'technology',
                'category_id' => 4,
                'description' => 'The rise of artificial intelligence in healthcare.',
                'quantity' => 5,
                'available_quantity' => 4,
                'location' => 'Section D - Shelf 2',
                'cover_image' => 'book_covers/technology (10).jpg',

            ],

            // History Books (category_id: 5)
            [
                'title' => 'The Virgin Suicides',
                'slug' => 'the-virgin-suicides',
                'author' => 'Jeffrey Eugenides',
                'isbn' => '9780374282264',
                'category_slug' => 'history',
                'category_id' => 5,
                'description' => 'A haunting tale of suburban tragedy and historical memory.',
                'quantity' => 5,
                'available_quantity' => 4,
                'location' => 'Section E - Shelf 1',
                'cover_image' => 'book_covers/history (1).jpg',

            ],
            [
                'title' => 'Stranger Beside Me',
                'slug' => 'stranger-beside-me',
                'author' => 'Ann Rule',
                'isbn' => '9780451203267',
                'category_slug' => 'history',
                'category_id' => 5,
                'description' => 'A true crime classic about Ted Bundy and the history of serial murder investigation.',
                'quantity' => 6,
                'available_quantity' => 5,
                'location' => 'Section E - Shelf 1',
                'cover_image' => 'book_covers/history (2).jpg',

            ],
            [
                'title' => 'A Room of One\'s Own',
                'slug' => 'a-room-of-ones-own',
                'author' => 'Virginia Woolf',
                'isbn' => '9780156787338',
                'category_slug' => 'history',
                'category_id' => 5,
                'description' => 'A foundational feminist text examining women\'s place in literary history.',
                'quantity' => 4,
                'available_quantity' => 3,
                'location' => 'Section E - Shelf 1',
                'cover_image' => 'book_covers/history (3).jpg',

            ],
            [
                'title' => 'The Devil in the White City',
                'slug' => 'the-devil-in-the-white-city',
                'author' => 'Erik Larson',
                'isbn' => '9780375725609',
                'category_slug' => 'history',
                'category_id' => 5,
                'description' => 'Murder, magic, and madness at the fair that changed America.',
                'quantity' => 7,
                'available_quantity' => 6,
                'location' => 'Section E - Shelf 1',
                'cover_image' => 'book_covers/history (4).jpg',

            ],
            [
                'title' => 'The Handmaid\'s Tale',
                'slug' => 'the-handmaids-tale',
                'author' => 'Margaret Atwood',
                'isbn' => '9780385490818',
                'category_slug' => 'history',
                'category_id' => 5,
                'description' => 'A dystopian novel reflecting on historical patterns of oppression.',
                'quantity' => 8,
                'available_quantity' => 7,
                'location' => 'Section E - Shelf 1',
                'cover_image' => 'book_covers/history (5).jpg',

            ],
            [
                'title' => 'The Diary of a Young Girl',
                'slug' => 'the-diary-of-a-young-girl',
                'author' => 'Anne Frank',
                'isbn' => '9780553296983',
                'category_slug' => 'history',
                'category_id' => 5,
                'description' => 'The personal diary of Anne Frank during World War II.',
                'quantity' => 6,
                'available_quantity' => 5,
                'location' => 'Section E - Shelf 2',
                'cover_image' => 'book_covers/history (6).jpg',

            ],
            [
                'title' => 'Girl, Interrupted',
                'slug' => 'girl-interrupted',
                'author' => 'Susanna Kaysen',
                'isbn' => '9780679746041',
                'category_slug' => 'history',
                'category_id' => 5,
                'description' => 'A memoir about mental health treatment in the 1960s.',
                'quantity' => 5,
                'available_quantity' => 4,
                'location' => 'Section E - Shelf 2',
                'cover_image' => 'book_covers/history (7).jpg',

            ],
            [
                'title' => 'The Yellow Wallpaper',
                'slug' => 'the-yellow-wallpaper',
                'author' => 'Charlotte Perkins Gilman',
                'isbn' => '9780486298566',
                'category_slug' => 'history',
                'category_id' => 5,
                'description' => 'A classic short story about women\'s mental health in the 19th century.',
                'quantity' => 4,
                'available_quantity' => 3,
                'location' => 'Section E - Shelf 2',
                'cover_image' => 'book_covers/history (8).jpg',

            ],
            [
                'title' => 'Helter Skelter',
                'slug' => 'helter-skelter',
                'author' => 'Vincent Bugliosi',
                'isbn' => '9780393087000',
                'category_slug' => 'history',
                'category_id' => 5,
                'description' => 'The true story of the Manson murders - a pivotal moment in American history.',
                'quantity' => 5,
                'available_quantity' => 4,
                'location' => 'Section E - Shelf 2',
                'cover_image' => 'book_covers/history (9).jpg',

            ],
            [
                'title' => 'In Cold Blood',
                'slug' => 'in-cold-blood',
                'author' => 'Truman Capote',
                'isbn' => '9780679745587',
                'category_slug' => 'history',
                'category_id' => 5,
                'description' => 'A groundbreaking work of true crime that changed literary history.',
                'quantity' => 6,
                'available_quantity' => 5,
                'location' => 'Section E - Shelf 2',
                'cover_image' => 'book_covers/history (10).jpg',

            ],

        ];

        foreach ($books as $book) {
            $category = $categories[$book['category_slug']];
            if ($category) {
                DB::table('books')->insert([
                    'title' => $book['title'],
                    'slug' => $book['slug'],
                    'author' => $book['author'],
                    'isbn' => $book['isbn'],
                    'category_id' => $category->id,
                    'description' => $book['description'],
                    'quantity' => $book['quantity'],
                    'available_quantity' => $book['available_quantity'],
                    'location' => $book['location'],
                    'cover_image' => $book['cover_image'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
