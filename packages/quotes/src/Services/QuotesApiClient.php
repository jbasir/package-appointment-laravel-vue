<?php

namespace Vendor\Quotes\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class QuotesApiClient
{
    /**
     * The HTTP client instance.
     *
     * @var Client
     */
    protected $client;

    /**
     * The base URL for the API.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * The maximum number of requests allowed per time window.
     *
     * @var int
     */
    protected $rateLimit;

    /**
     * The time window in seconds for rate limiting.
     *
     * @var int
     */
    protected $timeWindow;

    /**
     * Local cache for quotes (sorted by ID for binary search).
     *
     * @var array
     */
    protected $quotesCache = [];

    /**
     * Create a new API client instance.
     *
     * @param string $baseUrl
     * @param int $rateLimit
     * @param int $timeWindow
     */
    public function __construct(string $baseUrl, int $rateLimit, int $timeWindow)
    {
        $this->client = new Client();
        $this->baseUrl = $baseUrl;
        $this->rateLimit = $rateLimit;
        $this->timeWindow = $timeWindow;
    }

    /**
     * Get all quotes from the API.
     *
     * @return array
     * @throws GuzzleException
     */
    public function getAllQuotes(): array
    {
        $response = $this->makeRequest('GET', '/quotes');
        $quotes = json_decode($response, true)['quotes'] ?? [];
        
        // Update cache with sorted quotes
        $this->updateCache($quotes);
        
        return $quotes;
    }

    /**
     * Get a random quote from the API.
     *
     * @return array
     * @throws GuzzleException
     */
    public function getRandomQuote(): array
    {
        $response = $this->makeRequest('GET', '/quotes/random');
        return json_decode($response, true) ?? [];
    }

    /**
     * Get a specific quote by ID, using cache with binary search first.
     *
     * @param int $id
     * @return array
     * @throws GuzzleException
     */
    public function getQuote(int $id): array
    {
        // Check cache first using binary search
        $cachedQuote = $this->findQuoteInCache($id);
        
        if ($cachedQuote !== null) {
            return $cachedQuote;
        }
        
        // If not in cache, fetch from API
        $response = $this->makeRequest('GET', "/quotes/{$id}");
        $quote = json_decode($response, true) ?? [];
        
        // Add to cache
        if (!empty($quote) && isset($quote['id'])) {
            $this->updateCacheWithSingleQuote($quote);
        }
        
        return $quote;
    }

    /**
     * Make an API request with rate limiting.
     *
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return string
     * @throws GuzzleException
     */
    protected function makeRequest(string $method, string $endpoint, array $options = []): string
    {
        $this->applyRateLimit();
        
        $url = $this->baseUrl . $endpoint;
        $response = $this->client->request($method, $url, $options);
        
        // Add current timestamp to request log using Cache
        $timestamps = $this->getRequestTimestamps();
        $timestamps[] = time();
        $this->saveRequestTimestamps($timestamps);
        
        return $response->getBody()->getContents();
    }

    /**
     * Apply rate limiting logic.
     *
     * @return void
     */
    protected function applyRateLimit(): void
    {
        // Get timestamps from cache
        $timestamps = $this->getRequestTimestamps();
        
        // Log for debugging
        Log::debug('Rate limiting: Current timestamps count: ' . count($timestamps));
        Log::debug('Rate limiting: Configured limit: ' . $this->rateLimit);
        
        // Remove timestamps outside the current window
        $currentTime = time();
        $windowStart = $currentTime - $this->timeWindow;
        
        $timestamps = array_filter($timestamps, function ($timestamp) use ($windowStart) {
            return $timestamp >= $windowStart;
        });
        
        // Save filtered timestamps back to cache
        $this->saveRequestTimestamps($timestamps);
        
        Log::debug('Rate limiting: Filtered timestamps count: ' . count($timestamps));
        
        // If we're at the limit, wait until we can make another request
        if (count($timestamps) >= $this->rateLimit) {
            $oldestTimestamp = min($timestamps);
            $timeToWait = ($oldestTimestamp + $this->timeWindow) - $currentTime;
            
            if ($timeToWait > 0) {
                Log::info("Rate limit reached, waiting {$timeToWait} seconds before next request");
                sleep($timeToWait + 1); // Add 1 second buffer
            }
        }
    }

    /**
     * Get request timestamps from cache.
     *
     * @return array
     */
    protected function getRequestTimestamps(): array
    {
        return Cache::get('quotes_api_timestamps', []);
    }

    /**
     * Save request timestamps to cache.
     *
     * @param array $timestamps
     * @return void
     */
    protected function saveRequestTimestamps(array $timestamps): void
    {
        // Store timestamps for 2x the time window to ensure they don't expire too soon
        Cache::put('quotes_api_timestamps', $timestamps, now()->addSeconds($this->timeWindow * 2));
    }

    /**
     * Find a quote in the cache using binary search.
     *
     * @param int $id
     * @return array|null
     */
    protected function findQuoteInCache(int $id): ?array
    {
        if (empty($this->quotesCache)) {
            return null;
        }
        
        $low = 0;
        $high = count($this->quotesCache) - 1;
        
        while ($low <= $high) {
            $mid = (int)(($low + $high) / 2);
            $midQuoteId = $this->quotesCache[$mid]['id'];
            
            if ($midQuoteId === $id) {
                return $this->quotesCache[$mid];
            }
            
            if ($midQuoteId < $id) {
                $low = $mid + 1;
            } else {
                $high = $mid - 1;
            }
        }
        
        return null;
    }

    /**
     * Update the cache with a list of quotes, ensuring they're sorted by ID.
     *
     * @param array $quotes
     * @return void
     */
    protected function updateCache(array $quotes): void
    {
        // Merge new quotes with existing cache
        $this->quotesCache = array_merge($this->quotesCache, $quotes);
        
        // Remove duplicates by creating an associative array with ID as key
        $uniqueQuotes = [];
        foreach ($this->quotesCache as $quote) {
            if (isset($quote['id'])) {
                $uniqueQuotes[$quote['id']] = $quote;
            }
        }
        
        // Convert back to indexed array and sort by ID
        $this->quotesCache = array_values($uniqueQuotes);
        usort($this->quotesCache, function ($a, $b) {
            return $a['id'] <=> $b['id'];
        });
    }

    /**
     * Update cache with a single quote, maintaining sort order.
     *
     * @param array $quote
     * @return void
     */
    protected function updateCacheWithSingleQuote(array $quote): void
    {
        // Check if quote already exists in cache
        foreach ($this->quotesCache as $key => $cachedQuote) {
            if ($cachedQuote['id'] === $quote['id']) {
                // Update existing quote
                $this->quotesCache[$key] = $quote;
                return;
            }
        }
        
        // Add new quote and re-sort cache
        $this->quotesCache[] = $quote;
        usort($this->quotesCache, function ($a, $b) {
            return $a['id'] <=> $b['id'];
        });
    }
} 