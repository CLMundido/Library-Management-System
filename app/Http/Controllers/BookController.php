<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function catalog(Request $request)
    {
        // Mock data - replace with actual database queries
        $books = collect([
            [
                'id' => 1,
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'isbn' => '978-0-06-112008-4',
                'category' => 'Fiction',
                'available' => true,
                'cover_image' => null
            ],
            [
                'id' => 2,
                'title' => 'Charlotte\'s Web',
                'author' => 'E.B. White',
                'isbn' => '978-0-06-440055-8',
                'category' => 'Children',
                'available' => true,
                'cover_image' => null
            ],
            [
                'id' => 3,
                'title' => 'Harry Potter and the Sorcerer\'s Stone',
                'author' => 'J.K. Rowling',
                'isbn' => '978-0-439-70818-8',
                'category' => 'Fantasy',
                'available' => false,
                'cover_image' => null
            ],
            [
                'id' => 4,
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '978-0-452-28423-4',
                'category' => 'Fiction',
                'available' => true,
                'cover_image' => null
            ],
            [
                'id' => 5,
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'isbn' => '978-0-7432-7356-5',
                'category' => 'Fiction',
                'available' => true,
                'cover_image' => null
            ]
        ]);

        $categories = ['All Categories', 'Fiction', 'Non-Fiction', 'Science', 'History', 'Children', 'Fantasy'];
        
        // Filter by search query
        if ($request->has('search') && $request->search) {
            $books = $books->filter(function ($book) use ($request) {
                return stripos($book['title'], $request->search) !== false ||
                    stripos($book['author'], $request->search) !== false ||
                    stripos($book['isbn'], $request->search) !== false;
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category && $request->category !== 'All Categories') {
            $books = $books->filter(function ($book) use ($request) {
                return $book['category'] === $request->category;
            });
        }

        return view('book-catalog', compact('books', 'categories'));
    }

    public function ebooks()
    {
        // Mock data for e-books
        $continueReading = [
            [
                'id' => 1,
                'title' => 'Digital Marketing Fundamentals',
                'author' => 'John Smith',
                'progress' => 65,
                'pages' => 250
            ],
            [
                'id' => 2,
                'title' => 'Introduction to AI',
                'author' => 'Jane Doe',
                'progress' => 30,
                'pages' => 180
            ],
            [
                'id' => 3,
                'title' => 'Web Development Guide',
                'author' => 'Mike Johnson',
                'progress' => 80,
                'pages' => 320
            ]
        ];

        $availableEbooks = collect([
            [
                'id' => 4,
                'title' => 'Data Science Basics',
                'author' => 'Sarah Wilson',
                'pages' => 200,
                'category' => 'Science'
            ],
            [
                'id' => 5,
                'title' => 'Modern JavaScript',
                'author' => 'Tom Brown',
                'pages' => 150,
                'category' => 'Programming'
            ],
            [
                'id' => 6,
                'title' => 'Psychology Today',
                'author' => 'Dr. Lisa Chen',
                'pages' => 280,
                'category' => 'Psychology'
            ],
            [
                'id' => 7,
                'title' => 'History of Art',
                'author' => 'Robert Davis',
                'pages' => 350,
                'category' => 'Art'
            ]
        ]);

        $categories = ['All', 'Fiction', 'Non-Fiction', 'Academic', 'Science', 'Programming', 'Psychology', 'Art'];

        return view('read-ebooks', compact('continueReading', 'availableEbooks', 'categories'));
    }

    public function show($id)
    {
        // Mock book details - replace with actual database query
        $book = [
            'id' => $id,
            'title' => 'Sample Book Title',
            'author' => 'Author Name',
            'isbn' => '978-0-123456-78-9',
            'category' => 'Fiction',
            'description' => 'This is a sample book description that would contain details about the book content, plot, and other relevant information.',
            'available' => true,
            'total_copies' => 5,
            'available_copies' => 3,
            'publication_year' => 2020,
            'publisher' => 'Sample Publisher'
        ];

        return view('book-details', compact('book'));
    }
}