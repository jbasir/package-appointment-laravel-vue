<?php

namespace Vendor\Quotes\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Vendor\Quotes\Services\QuotesApiClient;

class QuotesApiClientTest extends TestCase
{
    protected $quotesApiClient;
    protected $mockHandler;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock handler
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        // Create a QuotesApiClient instance with the mocked client
        $this->quotesApiClient = new QuotesApiClient('https://dummyjson.com', 30, 60);
        
        // Replace the private client property with our mocked client
        $reflectionProperty = new \ReflectionProperty(QuotesApiClient::class, 'client');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->quotesApiClient, $client);
    }

    public function testGetAllQuotes()
    {
        // Set up the mock response
        $this->mockHandler->append(new Response(200, [], json_encode([
            'quotes' => [
                ['id' => 1, 'quote' => 'Test quote 1', 'author' => 'Author 1'],
                ['id' => 2, 'quote' => 'Test quote 2', 'author' => 'Author 2'],
            ]
        ])));

        // Call the method under test
        $result = $this->quotesApiClient->getAllQuotes();

        // Assert that the result is as expected
        $this->assertCount(2, $result);
        $this->assertEquals('Test quote 1', $result[0]['quote']);
        $this->assertEquals('Author 2', $result[1]['author']);
    }

    public function testGetRandomQuote()
    {
        // Set up the mock response
        $this->mockHandler->append(new Response(200, [], json_encode([
            'id' => 1, 
            'quote' => 'Random test quote', 
            'author' => 'Random Author'
        ])));

        // Call the method under test
        $result = $this->quotesApiClient->getRandomQuote();

        // Assert that the result is as expected
        $this->assertEquals('Random test quote', $result['quote']);
        $this->assertEquals('Random Author', $result['author']);
    }

    public function testGetQuote()
    {
        // Set up the mock response
        $this->mockHandler->append(new Response(200, [], json_encode([
            'id' => 42, 
            'quote' => 'Specific test quote', 
            'author' => 'Specific Author'
        ])));

        // Call the method under test
        $result = $this->quotesApiClient->getQuote(42);

        // Assert that the result is as expected
        $this->assertEquals(42, $result['id']);
        $this->assertEquals('Specific test quote', $result['quote']);
        $this->assertEquals('Specific Author', $result['author']);
    }

    public function testFindQuoteInCache()
    {
        // Create a quote and add it to the cache
        $quote = [
            'id' => 123,
            'quote' => 'Cached quote',
            'author' => 'Cached Author'
        ];

        // Use reflection to access and populate the private cache
        $reflectionProperty = new \ReflectionProperty(QuotesApiClient::class, 'quotesCache');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->quotesApiClient, [$quote]);

        // Use reflection to call the private findQuoteInCache method
        $reflectionMethod = new \ReflectionMethod(QuotesApiClient::class, 'findQuoteInCache');
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invoke($this->quotesApiClient, 123);

        // Assert that the cached quote was found
        $this->assertEquals($quote, $result);
        
        // Try to find a non-existent quote
        $result = $reflectionMethod->invoke($this->quotesApiClient, 999);
        
        // Assert that no quote was found
        $this->assertNull($result);
    }
} 