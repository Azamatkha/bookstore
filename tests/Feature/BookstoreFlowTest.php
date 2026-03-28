<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;

function bookstoreBook(array $overrides = []): Book
{
    $category = Category::create([
        'name' => 'Technology',
        'slug' => 'technology',
        'description' => 'Technology books',
    ]);

    $author = Author::create([
        'name' => 'Robert C. Martin',
        'born_year' => 1952,
        'bio' => 'Software craftsman',
    ]);

    return Book::create(array_merge([
        'title' => 'Clean Architecture',
        'slug' => 'clean-architecture',
        'description' => 'Architecture book',
        'price' => 30,
        'discount_price' => 25,
        'stock' => 8,
        'category_id' => $category->id,
        'author_id' => $author->id,
        'rating' => 4.7,
    ], $overrides));
}

test('non admins can not access the admin dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('admins can access the admin dashboard', function () {
    $admin = User::factory()->create([
        'is_admin' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk();
});

test('admins can access the admin api docs page', function () {
    $admin = User::factory()->create([
        'is_admin' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.api-docs'))
        ->assertOk()
        ->assertSee('/api/login')
        ->assertSee('admin@bookstore.test')
        ->assertSee('Bearer');
});

test('admin can login through api and receive sanctum token', function () {
    $admin = User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin-api@example.com',
        'password' => 'secret123',
        'is_admin' => true,
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $admin->email,
        'password' => 'secret123',
    ])->assertOk()
        ->assertJsonPath('token_type', 'Bearer')
        ->assertJsonPath('user.email', $admin->email)
        ->assertJsonPath('user.is_admin', true)
        ->assertJsonStructure([
            'token_type',
            'token',
            'user' => ['id', 'name', 'email', 'is_admin'],
        ]);

    $token = $response->json('token');

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/orders')
        ->assertOk();
});

test('admin can get all users orders via api', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $userOne = User::factory()->create();
    $userTwo = User::factory()->create();

    $book = bookstoreBook([
        'price' => 50,
        'discount_price' => null,
    ]);

    foreach ([$userOne, $userTwo] as $user) {
        $order = $user->orders()->create([
            'total_price' => 50,
            'status' => OrderStatus::Pending->value,
            'address' => 'Some address',
            'phone' => '+998900000000',
            'payment_type' => PaymentType::CashOnDelivery->value,
        ]);

        $order->items()->create([
            'book_id' => $book->id,
            'book_title' => $book->title,
            'quantity' => 1,
            'price' => 50,
        ]);
    }

    $token = $admin->createToken('api-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/orders')
        ->assertOk();

    expect(count($response->json()))->toBe(2);
});

test('public api responses do not include data or meta wrappers', function () {
    $book = bookstoreBook([
        'price' => 149000,
        'discount_price' => 119000,
    ]);

    $booksResponse = $this->getJson('/api/books');

    $booksResponse->assertOk();
    expect($booksResponse->json('data'))->toBeNull();
    expect($booksResponse->json('meta'))->toBeNull();
    expect($booksResponse->json('0.id'))->toBe($book->id);
    expect($booksResponse->json('0.price'))->toBe(149000);

    $bookResponse = $this->getJson('/api/books/' . $book->id);

    $bookResponse->assertOk();
    expect($bookResponse->json('data'))->toBeNull();
    expect($bookResponse->json('id'))->toBe($book->id);
    expect($bookResponse->json('price'))->toBe(149000);
});

test('categories and authors can be fetched by id through api', function () {
    $book = bookstoreBook();

    $categoryResponse = $this->getJson('/api/categories/' . $book->category_id);

    $categoryResponse->assertOk();
    expect($categoryResponse->json('data'))->toBeNull();
    expect($categoryResponse->json('id'))->toBe($book->category_id);

    $authorResponse = $this->getJson('/api/authors/' . $book->author_id);

    $authorResponse->assertOk();
    expect($authorResponse->json('data'))->toBeNull();
    expect($authorResponse->json('id'))->toBe($book->author_id);
});

test('order api returns plain order json without wrappers', function () {
    $user = User::factory()->create();
    $book = bookstoreBook([
        'price' => 19,
        'discount_price' => null,
    ]);

    $order = $user->orders()->create([
        'total_price' => 19,
        'status' => OrderStatus::Confirmed->value,
        'address' => 'Tashkent,Tashkent,12345',
        'phone' => '+99833424019',
        'payment_type' => PaymentType::CashOnDelivery->value,
    ]);

    $order->items()->create([
        'book_id' => null,
        'book_title' => 'Atomic Habits',
        'quantity' => 1,
        'price' => 19,
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    $ordersResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/orders');

    $ordersResponse->assertOk();
    expect($ordersResponse->json('data'))->toBeNull();
    expect($ordersResponse->json('0.id'))->toBe($order->id);
    expect($ordersResponse->json('0.total_price'))->toBe(19);

    $orderResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/orders/' . $order->id);

    $orderResponse->assertOk();
    expect($orderResponse->json('data'))->toBeNull();
    expect($orderResponse->json('id'))->toBe($order->id);
    expect($orderResponse->json('total_price'))->toBe(19);
    expect($orderResponse->json('items.0.book_title'))->toBe('Atomic Habits');
    expect($orderResponse->json('items.0.subtotal'))->toBe(19);
});

test('users can place an order and stock is reduced', function () {
    $user = User::factory()->create();
    $book = bookstoreBook();

    $this->withSession([
        'bookstore.cart' => [$book->id => 2],
    ])->actingAs($user)->post(route('checkout.store'), [
        'address' => '221B Baker Street',
        'phone' => '+123456789',
        'payment_type' => PaymentType::CashOnDelivery->value,
    ])->assertRedirect();

    $order = Order::first();

    expect($order)->not->toBeNull();
    expect($order->status)->toBe(OrderStatus::Pending);
    expect($order->items)->toHaveCount(1);
    expect($order->items->first()->quantity)->toBe(2);
    expect($book->fresh()->stock)->toBe(6);
});

test('authenticated users can view wallet page and get a generated card', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('wallet.show'))
        ->assertOk();

    $wallet = Wallet::where('user_id', $user->id)->first();

    expect($wallet)->not->toBeNull();
    expect($wallet->card_number)->toHaveLength(16);
    expect($wallet->balance)->toBe(0);
});

test('users can top up wallet with mock card details', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('wallet.top-up'), [
            'card_number' => '8600123412341234',
            'expiry' => now()->addYear()->format('m/Y'),
            'amount' => 150000,
        ])
        ->assertRedirect(route('wallet.show'));

    $wallet = Wallet::where('user_id', $user->id)->first();
    $transaction = WalletTransaction::first();

    expect($wallet)->not->toBeNull();
    expect($wallet->balance)->toBe(150000);
    expect($transaction)->not->toBeNull();
    expect($transaction->wallet_id)->toBe($wallet->id);
    expect($transaction->type)->toBe('top_up');
    expect($transaction->amount)->toBe(150000);
    expect($transaction->balance_after)->toBe(150000);
});

test('users can place an order with card payment when wallet has enough balance', function () {
    $user = User::factory()->create();
    $book = bookstoreBook([
        'discount_price' => null,
        'price' => 70000,
    ]);

    Wallet::create([
        'user_id' => $user->id,
        'card_number' => '8600000000000001',
        'balance' => 200000,
    ]);

    $this->withSession([
        'bookstore.cart' => [$book->id => 2],
    ])->actingAs($user)->post(route('checkout.store'), [
        'address' => 'Wallet street',
        'phone' => '+998901112233',
        'payment_type' => PaymentType::Card->value,
    ])->assertRedirect();

    $order = Order::first();
    $transaction = WalletTransaction::first();

    expect($book->fresh()->stock)->toBe(6);
    expect($user->wallet()->first()->balance)->toBe(60000);
    expect($order->wallet_id)->toBe($user->wallet()->first()->id);
    expect($order->wallet_card_number)->toBe('8600000000000001');
    expect($transaction)->not->toBeNull();
    expect($transaction->wallet_id)->toBe($user->wallet()->first()->id);
    expect($transaction->order_id)->toBe($order->id);
    expect($transaction->type)->toBe('order_payment');
    expect($transaction->amount)->toBe(140000);
    expect($transaction->balance_after)->toBe(60000);
});

test('users can not place an order with card payment when wallet balance is low', function () {
    $user = User::factory()->create();
    $book = bookstoreBook([
        'discount_price' => null,
        'price' => 90000,
    ]);

    Wallet::create([
        'user_id' => $user->id,
        'card_number' => '8600000000000002',
        'balance' => 50000,
    ]);

    $this->withSession([
        'bookstore.cart' => [$book->id => 1],
    ])->actingAs($user)->post(route('checkout.store'), [
        'address' => 'Wallet street',
        'phone' => '+998901112233',
        'payment_type' => PaymentType::Card->value,
    ])->assertSessionHasErrors('payment_type');

    expect(Order::count())->toBe(0);
    expect($book->fresh()->stock)->toBe(8);
    expect($user->wallet()->first()->balance)->toBe(50000);
});

test('users can only view their own orders', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $book = bookstoreBook();

    $order = $otherUser->orders()->create([
        'total_price' => 25,
        'status' => OrderStatus::Pending->value,
        'address' => 'Another address',
        'phone' => '+998900000000',
        'payment_type' => PaymentType::CashOnDelivery->value,
    ]);

    $order->items()->create([
        'book_id' => $book->id,
        'book_title' => $book->title,
        'quantity' => 1,
        'price' => 25,
    ]);

    $this->actingAs($user)
        ->get(route('orders.show', $order))
        ->assertForbidden();
});

test('admin can get all wallets via api', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create([
        'name' => 'Wallet User',
        'email' => 'wallet-user@example.com',
    ]);

    Wallet::create([
        'user_id' => $user->id,
        'card_number' => '8600000000000011',
        'balance' => 420000,
    ]);

    $token = $admin->createToken('api-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/wallets')
        ->assertOk();

    expect($response->json('0.user_id'))->toBe($user->id);
    expect($response->json('0.card_number'))->toBe('8600000000000011');
    expect($response->json('0.balance'))->toBe(420000);
});

test('admin can get wallet transactions via api', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();
    $wallet = Wallet::create([
        'user_id' => $user->id,
        'card_number' => '8600000000000099',
        'balance' => 100000,
    ]);

    WalletTransaction::create([
        'wallet_id' => $wallet->id,
        'order_id' => null,
        'type' => 'top_up',
        'amount' => 100000,
        'balance_after' => 100000,
    ]);

    $token = $admin->createToken('api-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/wallets/' . $wallet->id . '/transactions')
        ->assertOk();

    expect($response->json('wallet.id'))->toBe($wallet->id);
    expect($response->json('wallet.card_number'))->toBe('8600000000000099');
    expect($response->json('transactions.0.type'))->toBe('top_up');
});

test('non admin users can not get all wallets via api', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/wallets')
        ->assertForbidden();
});

test('non admin users can not get wallet transactions via api', function () {
    $user = User::factory()->create();
    $wallet = Wallet::create([
        'user_id' => $user->id,
        'card_number' => '8600000000000077',
        'balance' => 50000,
    ]);
    $token = $user->createToken('api-token')->plainTextToken;

    $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/wallets/' . $wallet->id . '/transactions')
        ->assertForbidden();
});
