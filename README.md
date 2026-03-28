# Bookstore Laravel App

This repository hosts a Laravel 12 bookstore with storefront and admin experiences. It shows a clear separation between data models, domain services, HTTP controllers (web/API), and Blade views.

## Project Structure

1. ** Models & Data **
   - `app/Models/User`, `Book`, `Author`, `Category`, `Order`, `OrderItem` define the core domain. Orders use `app/Enums/OrderStatus` and `app/Enums/PaymentType`, and helper attributes (`currentPrice`, `subtotal`) keep pricing logic in the models.
   - Additional enums and models (e.g., carts/orders) are supported by services under `app/Services` and `app/Support/Currency` for money formatting.

2. ** Services **
   - `CartService` manages session-based cart state, calculates totals, and translates session items into order lines.
   - `OrderService` handles checkout: it locks stock, validates items, creates `Order` and `OrderItem` records, decrements stock, and ensures the nested creation happens inside a DB transaction for consistency.

3. ** Web Controllers & Views **
   - Storefront controllers live under `app/Http/Controllers/Storefront`: `HomeController` (listing/filtering books), `CartController`, `CheckoutController`, and order listing/detail controllers render Blade views in `resources/views/storefront`.
   - Admin controllers under `app/Http/Controllers/Admin` manage dashboards, books, categories, authors, and order lifecycle. The admin layout is defined in `resources/views/layouts/admin.blade.php` with sidebar partials.
   - Authentication uses Breeze-style controllers/routes in `routes/auth.php` with views under `resources/views/auth`.

4. ** API Layer **
   - `routes/api.php` exposes Sanctum-authenticated endpoints for login/register (`App\Http\Controllers\Api\AuthController`), books, authors, categories, and order operations (`App\Http\Controllers\Api\OrderController`).
   - The API mirrors storefront behavior (orders created through `OrderService`, authenticated users can list/view their own orders).

5. ** Routes **
   - `routes/web.php` wires storefront pages (`/`, `/books/{book}`), cart actions, checkout, user profile, and admin panel (protected by `EnsureUserIsAdmin`).
   - API routes are grouped with `auth:sanctum` for protected resources.

6. ** Views & Components **
   - Blade layouts (`layouts/store.blade.php`, `layouts/admin.blade.php`) wrap headers/footers/sidebars. Shared components like `book-card`, `status-badge`, and `primary-button` keep markup consistent.
   - Storefront pages include catalog, cart, checkout, orders, and account views; admin page views cover dashboard metrics, orders list/detail, and management of resources.

7. ** Data & Migrations **
   - The schema is defined in `database/migrations` (users, books, authors, categories, orders, order items). Prices are stored as integers with migrations converting decimals to integers for precision.
   - `database/seeders/DatabaseSeeder` seeds admin/customer users plus sample categories, authors, and books.

8. ** Testing & Tooling **
   - Tests live under `tests/Feature` (Breeze auth flows, storefront access, API expectations) and leverage Pest with `tests/Pest.php` bootstrapping `RefreshDatabase`.
   - Composer scripts define `setup`, `dev`, and `test` helpers.

## Development Flow

1. Run `composer install` and `npm install` to pull dependencies.
2. Configure `.env` (database, app URL, etc.) and run `php artisan migrate --seed` to prepare the schema/data.
3. Use `php artisan serve` (or `npm run dev`) while editing Blade views (in `resources/views`) and controllers/services under `app/Http/Controllers` and `app/Services`.
4. Access `/` for the storefront, `/admin` for the admin dashboard, and `/api` (with Sanctum token) for JSON endpoints.

## Next Steps

- Extend the existing wallet/payment system when adding balance tracking, transaction history, and wallet-backed order placement.
- Build Cypress/feature tests for wallet flows once implemented.
