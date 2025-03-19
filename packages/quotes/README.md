# Laravel Quotes Package

A Laravel package that provides an interface for the DummyJSON quotes API with rate limiting, caching, binary search for efficient data retrieval, and a beautiful Vue.js UI.

## Features

- 🔌 Simple integration with the [DummyJSON Quotes API](https://dummyjson.com/quotes)
- 🚦 Built-in rate limiting to prevent API abuse
- 📦 Local cache mechanism with binary search for efficient retrieval
- 🎨 Pre-built Vue.js UI for displaying and interacting with quotes
- 🔧 Fully customizable through configuration file

## Requirements

- PHP 8.2+
- Laravel 10+ / 11+ / 12+
- Node.js and NPM (for Vue.js UI development)

## Installation

### 1. Add the package to your Laravel application

```bash
composer require vendor/quotes
```

### 2. Publish the package assets

```bash
php artisan vendor:publish --provider="Vendor\Quotes\QuotesServiceProvider" --tag="quotes-assets"
```

### 3. (Optional) Publish the configuration file

```bash
php artisan vendor:publish --provider="Vendor\Quotes\QuotesServiceProvider" --tag="quotes-config"
```

### 4. (Optional) Publish the views for customization

```bash
php artisan vendor:publish --provider="Vendor\Quotes\QuotesServiceProvider" --tag="quotes-views"
```

## Configuration

After publishing the configuration file, you can find it at `config/quotes.php`. Here's what you can configure:

```php
return [
    // Base URL for the quotes API
    'api_url' => env('QUOTES_API_URL', 'https://dummyjson.com'),

    // Maximum number of requests allowed within the specified time window
    'rate_limit' => env('QUOTES_RATE_LIMIT', 30),

    // Time window in seconds for the rate limit
    'time_window' => env('QUOTES_TIME_WINDOW', 60),

    // Duration in seconds for which quotes will be cached
    'cache_duration' => env('QUOTES_CACHE_DURATION', 3600),
];
```

You can also set these values in your `.env` file:

```
QUOTES_API_URL=https://dummyjson.com
QUOTES_RATE_LIMIT=30
QUOTES_TIME_WINDOW=60
QUOTES_CACHE_DURATION=3600
```

## Usage

### Accessing the Vue.js UI

Once installed, you can access the pre-built Vue.js UI at:

```
https://your-app.test/quotes-ui
```

### Using the API in your application

#### Get all quotes

```php
use Vendor\Quotes\Services\QuotesApiClient;

public function index(QuotesApiClient $quotesApiClient)
{
    $quotes = $quotesApiClient->getAllQuotes();
    return view('your-view', compact('quotes'));
}
```

#### Get a random quote

```php
use Vendor\Quotes\Services\QuotesApiClient;

public function randomQuote(QuotesApiClient $quotesApiClient)
{
    $quote = $quotesApiClient->getRandomQuote();
    return view('your-view', compact('quote'));
}
```

#### Get a specific quote by ID

```php
use Vendor\Quotes\Services\QuotesApiClient;

public function showQuote(QuotesApiClient $quotesApiClient, $id)
{
    $quote = $quotesApiClient->getQuote($id);
    return view('your-view', compact('quote'));
}
```

### Direct API Endpoints

You can also directly access the API endpoints provided by this package:

- `GET /api/quotes` - Get all quotes
- `GET /api/quotes/random` - Get a random quote
- `GET /api/quotes/{id}` - Get a specific quote by ID

## Rate Limiting

The package implements a simple time-window based rate limiting mechanism. It keeps track of request timestamps and will automatically pause execution if the rate limit is reached, waiting until it's safe to make the next request.

## Caching with Binary Search

The package automatically caches quotes retrieved from the API. When requesting a specific quote by ID, it first checks the local cache using a binary search algorithm (which is efficient for sorted data). The cache is kept sorted by quote ID to facilitate binary search.

## Customizing the Vue.js UI

### Modifying the frontend code

If you want to customize the Vue.js UI, you'll need to:

1. Clone this repository or download the source code
2. Navigate to the `resources/js` directory
3. Install dependencies:

```bash
npm install
```

4. Make your changes to the Vue.js code
5. Build the assets:

```bash
npm run build
```

6. Copy the built assets to your Laravel application's public directory:

```bash
cp -r dist/ /path/to/your/laravel/app/public/vendor/quotes/
```

### Frontend structure

The Vue.js application uses Vue 3 with Vue Router and includes:

- A main layout component
- Views for listing all quotes, displaying a random quote, and searching quotes by ID
- A reusable quote card component
- TailwindCSS for styling

## Testing

To run the tests:

```bash
vendor/bin/phpunit
```

## License

This package is open-sourced software licensed under the MIT license. 