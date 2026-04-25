# Laravel Simple Analytics

[![Latest Version on Packagist](https://img.shields.io/packagist/v/justawebdev/laravel-simple-analytics.svg?style=flat-square)](https://packagist.org/packages/justawebdev/laravel-simple-analytics)
[![Total Downloads](https://img.shields.io/packagist/dt/justawebdev/laravel-simple-analytics.svg?style=flat-square)](https://packagist.org/packages/justawebdev/laravel-simple-analytics)
[![Laravel Version](https://img.shields.io/badge/laravel-10%2B-red.svg?style=flat-square)](https://laravel.com)
[![License](https://img.shields.io/packagist/l/justawebdev/laravel-simple-analytics.svg?style=flat-square)](https://packagist.org/packages/justawebdev/laravel-simple-analytics)

A lightweight, server-side analytics package for Laravel applications. Track page views, custom events, and generate simple reports without relying on third-party services.

## ✨ Features

- 🚀 **Zero Dependencies** - Pure Laravel implementation
- 📊 **Page View Tracking** - Automatic middleware-based tracking
- 🎯 **Custom Events** - Track any event with metadata
- 🛡️ **Route Filtering** - Ignore specific routes (like admin panels)
- 📈 **Simple Reports** - Built-in console command for top pages
- 🎨 **Facade & Helpers** - Easy-to-use API
- ⚡ **Performance Focused** - Minimal overhead

## 📦 Installation

Install the package via Composer:

```bash
composer require justawebdev/laravel-simple-analytics
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="JustAWebDev\Analytics\AnalyticsServiceProvider"
```

Run the migration to create the analytics table:

```bash
php artisan migrate
```

## ⚙️ Configuration

After publishing, you'll find the config file at `config/analytics.php`. Here's what you can configure:

```php
return [
    'enabled' => true,  // Enable/disable analytics globally

    'ignore_routes' => [
        'telescope/*',  // Ignore Laravel Telescope routes
        'horizon/*',    // Ignore Laravel Horizon routes
        // Add any routes you want to ignore
    ],
];
```

## 🚀 Usage

### Automatic Page View Tracking

Add the middleware to your `app/Http/Kernel.php` or route group:

```php
// In routes/web.php or api.php
Route::middleware(['analytics'])->group(function () {
    // Your routes here
});

// Or globally in Kernel.php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \JustAWebDev\Analytics\Middleware\TrackPageView::class,
    ],
];
```

### Manual Event Tracking

Use the facade to track custom events:

```php
use JustAWebDev\Analytics\Facades\Analytics;

// Track a simple event
Analytics::track('user_login');

// Track with metadata
Analytics::track('purchase', [
    'product_id' => 123,
    'amount' => 99.99,
    'currency' => 'USD'
]);
```

### Using the Helper Function

```php
// Global helper function
analytics()->track('button_click', ['button' => 'hero_cta']);
```

### Getting Analytics Data

```php
// Count total page views
$totalViews = analytics()->count('pageview');

// Count views for a specific page
$pageViews = analytics()->count('pageview', '/home');

// Get top 5 pages
$topPages = analytics()->topPages(5);

// Returns collection with 'name' and 'total' columns
foreach ($topPages as $page) {
    echo "{$page->name}: {$page->total} views\n";
}
```

### Console Reports

Generate a quick report from the command line:

```bash
php artisan analytics:report
```

Output:
```
Top Pages:
/home (150)
/about (89)
/contact (45)
```

## 🔧 Advanced Usage

### Custom Middleware Implementation

If you need more control, you can create your own middleware:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use JustAWebDev\Analytics\Facades\Analytics;

class CustomAnalytics
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Only track GET requests
        if ($request->isMethod('get')) {
            Analytics::pageView([
                'name' => $request->path(),
                'method' => $request->method(),
                'status' => $response->getStatusCode(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }
}
```

### Database Schema

The package creates an `analytics_events` table with these columns:

- `id` - Primary key
- `type` - Event type (pageview, event)
- `name` - Event name or page path
- `meta` - JSON metadata (nullable)
- `method` - HTTP method (nullable)
- `status` - HTTP status code (nullable)
- `duration_ms` - Response time in milliseconds (nullable)
- `created_at` / `updated_at` - Timestamps

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

## 📄 License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Credits

- [Justin Thomas](https://github.com/justawebdev) - Creator
- [Laravel Community](https://laravel.com) - Inspiration

---

Made with ❤️ for the Laravel community