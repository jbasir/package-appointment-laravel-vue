<?php

namespace Vendor\Quotes\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Vendor\Quotes\Services\QuotesApiClient;

class QuotesApiController extends Controller
{
    /**
     * The quotes API client instance.
     *
     * @var QuotesApiClient
     */
    protected $quotesApiClient;

    /**
     * Create a new controller instance.
     *
     * @param QuotesApiClient $quotesApiClient
     */
    public function __construct(QuotesApiClient $quotesApiClient)
    {
        $this->quotesApiClient = $quotesApiClient;
    }

    /**
     * Get all quotes.
     *
     * @return JsonResponse
     */
    public function getAllQuotes(): JsonResponse
    {
        try {
            $quotes = $this->quotesApiClient->getAllQuotes();
            return response()->json([
                'success' => true,
                'data' => $quotes
            ]);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Get a random quote.
     *
     * @return JsonResponse
     */
    public function getRandomQuote(): JsonResponse
    {
        try {
            $quote = $this->quotesApiClient->getRandomQuote();
            return response()->json([
                'success' => true,
                'data' => $quote
            ]);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Get a specific quote by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getQuote(int $id): JsonResponse
    {
        try {
            $quote = $this->quotesApiClient->getQuote($id);
            return response()->json([
                'success' => true,
                'data' => $quote
            ]);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Handle error responses.
     *
     * @param \Exception $exception
     * @return JsonResponse
     */
    protected function handleError(\Exception $exception): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $exception->getMessage(),
            'trace' => config('app.debug') ? $exception->getTrace() : null
        ], 500);
    }
} 