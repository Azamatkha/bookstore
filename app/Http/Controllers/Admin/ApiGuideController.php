<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class ApiGuideController extends Controller
{
    public function __invoke(): View
    {
        $baseUrl = rtrim(config('app.url'), '/');

        $tokenSteps = [
            'Send POST ' . $baseUrl . '/api/login',
            'Use your admin email and password in the JSON body.',
            'Copy the token value from the response.',
            'In Postman, add header Authorization: Bearer YOUR_TOKEN',
            'Use the same token for protected routes like /api/orders and /api/logout.',
        ];

        $endpoints = [
            [
                'method' => 'POST',
                'path' => '/api/login',
                'auth' => 'No',
                'description' => 'Login with email and password. Returns a Sanctum token. You can use your admin account here too.',
                'request' => [
                    'email' => 'admin@bookstore.test',
                    'password' => 'password',
                ],
                'response' => [
                    'token_type' => 'Bearer',
                    'token' => '1|example_admin_token',
                    'user' => [
                        'id' => 1,
                        'name' => 'Bookstore Admin',
                        'email' => 'admin@bookstore.test',
                        'is_admin' => true,
                    ],
                ],
            ],
            [
                'method' => 'POST',
                'path' => '/api/register',
                'auth' => 'No',
                'description' => 'Create a new user account and return a token immediately.',
                'request' => [
                    'name' => 'New User',
                    'email' => 'user@example.com',
                    'password' => 'password',
                    'password_confirmation' => 'password',
                ],
                'response' => [
                    'token_type' => 'Bearer',
                    'token' => '2|example_user_token',
                    'user' => [
                        'id' => 7,
                        'name' => 'New User',
                        'email' => 'user@example.com',
                        'is_admin' => false,
                    ],
                ],
            ],
            [
                'method' => 'POST',
                'path' => '/api/logout',
                'auth' => 'Bearer token',
                'description' => 'Logout the current token.',
                'request' => null,
                'response' => [
                    'message' => 'Logged out successfully.',
                ],
            ],
            [
                'method' => 'GET',
                'path' => '/api/books',
                'auth' => 'No',
                'description' => 'Get all books. You can filter with search, category, author, sort, min_price, max_price.',
                'request' => null,
                'response' => [
                    [
                        [
                            'id' => 1,
                            'title' => 'Atomic Habits',
                            'price' => 149000,
                            'discount_price' => 119000,
                        ],
                    ],
                ],
            ],
            [
                'method' => 'GET',
                'path' => '/api/books/{id}',
                'auth' => 'No',
                'description' => 'Get one book by numeric id.',
                'request' => null,
                'response' => [
                    'id' => 1,
                    'title' => 'Atomic Habits',
                    'description' => 'A practical guide to building good habits.',
                    'price' => 149000,
                    'discount_price' => 119000,
                ],
            ],
            [
                'method' => 'GET',
                'path' => '/api/categories',
                'auth' => 'No',
                'description' => 'Get all categories.',
                'request' => null,
                'response' => [
                    [
                        [
                            'id' => 1,
                            'name' => 'Technology',
                            'books_count' => 2,
                        ],
                    ],
                ],
            ],
            [
                'method' => 'GET',
                'path' => '/api/categories/{id}',
                'auth' => 'No',
                'description' => 'Get one category by numeric id.',
                'request' => null,
                'response' => [
                    'id' => 1,
                    'name' => 'Technology',
                    'slug' => 'technology',
                    'description' => 'Technology books',
                    'books_count' => 2,
                ],
            ],
            [
                'method' => 'GET',
                'path' => '/api/authors',
                'auth' => 'No',
                'description' => 'Get all authors.',
                'request' => null,
                'response' => [
                    [
                        [
                            'id' => 1,
                            'name' => 'Robert C. Martin',
                            'books_count' => 1,
                        ],
                    ],
                ],
            ],
            [
                'method' => 'GET',
                'path' => '/api/authors/{id}',
                'auth' => 'No',
                'description' => 'Get one author by numeric id.',
                'request' => null,
                'response' => [
                    'id' => 1,
                    'name' => 'Robert C. Martin',
                    'bio' => 'Software craftsman',
                    'born_year' => 1952,
                    'books_count' => 1,
                ],
            ],
            [
                'method' => 'GET',
                'path' => '/api/orders',
                'auth' => 'Bearer token',
                'description' => 'Get orders for the currently authenticated user.',
                'request' => null,
                'response' => [
                    [
                        [
                            'id' => 12,
                            'status' => 'pending',
                            'total_price' => 238000,
                        ],
                    ],
                ],
            ],
            [
                'method' => 'GET',
                'path' => '/api/orders/{id}',
                'auth' => 'Bearer token',
                'description' => 'Get one order if it belongs to the logged-in user.',
                'request' => null,
                'response' => [
                    'id' => 12,
                    'status' => 'pending',
                    'total_price' => 238000,
                    'items' => [
                        [
                            'book_id' => 1,
                            'quantity' => 2,
                            'price' => 119000,
                        ],
                    ],
                ],
            ],
            [
                'method' => 'POST',
                'path' => '/api/orders',
                'auth' => 'Bearer token',
                'description' => 'Create a new order. Stock is reduced automatically.',
                'request' => [
                    'address' => 'Tashkent, Chilanzar district, 12',
                    'phone' => '+998901234567',
                    'payment_type' => 'card',
                    'items' => [
                        ['book_id' => 1, 'quantity' => 2],
                        ['book_id' => 3, 'quantity' => 1],
                    ],
                ],
                'response' => [
                    'id' => 15,
                    'status' => 'pending',
                    'total_price' => 437000,
                ],
            ],
            [
                'method' => 'GET',
                'path' => '/api/wallets',
                'auth' => 'Bearer token (admin only)',
                'description' => 'Get all users wallets (admin endpoint).',
                'request' => null,
                'response' => [
                    [
                        [
                            'id' => 1,
                            'user_id' => 7,
                            'user_name' => 'New User',
                            'user_email' => 'user@example.com',
                            'card_number' => '8600123412341234',
                            'balance' => 120000,
                            'orders_paid_count' => 3,
                            'orders_paid_total' => 560000,
                        ],
                    ],
                ],
            ],
            [
                'method' => 'GET',
                'path' => '/api/wallets/{id}/transactions',
                'auth' => 'Bearer token (admin only)',
                'description' => 'Get wallet transaction history (top-up and order payments).',
                'request' => null,
                'response' => [
                    'wallet' => [
                        'id' => 1,
                        'user_id' => 7,
                        'card_number' => '8600123412341234',
                        'balance' => 120000,
                    ],
                    'transactions' => [
                        [
                            'id' => 10,
                            'type' => 'top_up',
                            'amount' => 300000,
                            'balance_after' => 300000,
                            'order_id' => null,
                        ],
                        [
                            'id' => 11,
                            'type' => 'order_payment',
                            'amount' => 180000,
                            'balance_after' => 120000,
                            'order_id' => 21,
                        ],
                    ],
                ],
            ],
        ];

        return view('admin.api-docs', compact('baseUrl', 'tokenSteps', 'endpoints'));
    }
}
