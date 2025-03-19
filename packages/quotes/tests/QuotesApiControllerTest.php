<?php

namespace Vendor\Quotes\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;
use Vendor\Quotes\QuotesServiceProvider;
use Vendor\Quotes\Services\QuotesApiClient;

class QuotesApiControllerTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            QuotesServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the QuotesApiClient service
        $this->mock(QuotesApiClient::class, function ($mock) {
            $mock->shouldReceive('getAllQuotes')->andReturn([
                ['id' => 1, 'quote' => 'Test quote 1', 'author' => 'Author 1'],
                ['id' => 2, 'quote' => 'Test quote 2', 'author' => 'Author 2'],
            ]);

            $mock->shouldReceive('getRandomQuote')->andReturn(
                ['id' => 3, 'quote' => 'Random test quote', 'author' => 'Random Author']
            );

            $mock->shouldReceive('getQuote')->with(42)->andReturn(
                ['id' => 42, 'quote' => 'Specific test quote', 'author' => 'Specific Author']
            );
        });

        // Configure the API routes
        Config::set('app.debug', true);
    }

    public function testGetAllQuotes()
    {
        $response = $this->getJson('/api/quotes');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    ['id' => 1, 'quote' => 'Test quote 1', 'author' => 'Author 1'],
                    ['id' => 2, 'quote' => 'Test quote 2', 'author' => 'Author 2'],
                ]
            ]);
    }

    public function testGetRandomQuote()
    {
        $response = $this->getJson('/api/quotes/random');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => 3,
                    'quote' => 'Random test quote',
                    'author' => 'Random Author'
                ]
            ]);
    }

    public function testGetQuote()
    {
        $response = $this->getJson('/api/quotes/42');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => 42,
                    'quote' => 'Specific test quote',
                    'author' => 'Specific Author'
                ]
            ]);
    }
} 