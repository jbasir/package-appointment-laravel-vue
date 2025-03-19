<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    |
    | This is the base URL for the quotes API.
    |
    */
    'api_url' => env('QUOTES_API_URL', 'https://dummyjson.com'),

    /*
    |--------------------------------------------------------------------------
    | Rate Limit
    |--------------------------------------------------------------------------
    |
    | Maximum number of requests allowed within the specified time window.
    |
    */
    'rate_limit' => env('QUOTES_RATE_LIMIT', 5),

    /*
    |--------------------------------------------------------------------------
    | Time Window
    |--------------------------------------------------------------------------
    |
    | Time window in seconds for the rate limit.
    |
    */
    'time_window' => env('QUOTES_TIME_WINDOW', 60),

    /*
    |--------------------------------------------------------------------------
    | Cache Duration
    |--------------------------------------------------------------------------
    |
    | Duration in seconds for which quotes will be cached.
    |
    */
    'cache_duration' => env('QUOTES_CACHE_DURATION', 3600),
]; 