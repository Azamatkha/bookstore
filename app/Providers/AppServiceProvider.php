<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        Blade::directive('money', function (string $expression): string {
            return "<?php echo \\App\\Support\\Currency::format({$expression}); ?>";
        });

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
