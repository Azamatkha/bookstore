<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'books' => Book::count(),
            'categories' => Category::count(),
            'authors' => Author::count(),
            'orders' => Order::count(),
            'revenue' => (float) Order::sum('total_price'),
        ];

        $latestOrders = Order::query()
            ->with('user')
            ->latest()
            ->take(6)
            ->get();

        $lowStockBooks = Book::query()
            ->with('author')
            ->orderBy('stock')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestOrders', 'lowStockBooks'));
    }
}
