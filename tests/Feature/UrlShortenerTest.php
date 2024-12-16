<?php

use App\Services\UrlShortenerService;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    $this->urlShortenerService = new UrlShortenerService();
    Cache::flush();
});

it('encodes a URL successfully', function () {
    $response = $this->postJson(route('encode'), ['url' => 'https://www.example.com']);

    $response->assertStatus(200)
        ->assertApiResponseStructure()
        ->assertJsonFragment([
            'message' => 'URL encoded successfully'
        ])
        ->assertJsonStructure(['data' => ['short_url']]);
});

it('decodes a short URL successfully', function () {
    $originalUrl = 'https://www.example.com';
    $shortUrl = $this->urlShortenerService->encode($originalUrl);

    $response = $this->postJson(route('decode'), ['short_url' => $shortUrl]);

    $response->assertStatus(200)
        ->assertApiResponseStructure()
        ->assertJsonFragment([
            'message' => 'URL decoded successfully',
            'original_url' => $originalUrl
        ]);
});

it('returns 404 for unknown short URL', function () {
    $response = $this->postJson(route('decode'), ['short_url' => url('/unknown')]);

    $response->assertStatus(404)
        ->assertApiResponseStructure()
        ->assertJsonFragment([
            'message' => 'Short URL not found'
        ]);
});

it('validates URL on encode', function () {
    $response = $this->postJson(route('encode'), ['url' => 'not-a-valid-url']);

    $response->assertStatus(422)
        ->assertApiResponseStructure()
        ->assertJsonFragment([
            'message' => 'Validation errors occurred'
        ])
        ->assertJsonStructure(['errors' => ['url']]);
});

it('validates short URL on decode', function () {
    $response = $this->postJson(route('decode'), ['shortUrl' => '']);

    $response->assertStatus(422)
        ->assertApiResponseStructure()
        ->assertJsonFragment([
            'message' => 'Validation errors occurred'
        ])
        ->assertJsonStructure(['errors' => ['short_url']]);
});

it('encodes the same URL to the same short URL', function () {
    $url = 'https://www.example.com';
    $shortUrl1 = $this->urlShortenerService->encode($url);
    $shortUrl2 = $this->urlShortenerService->encode($url);

    expect($shortUrl1)->toBe($shortUrl2);
});

it('ensures unique short URLs for different original URLs', function () {
    $url1 = 'https://www.example.com/page1';
    $url2 = 'https://www.example.com/page2';

    $shortUrl1 = $this->urlShortenerService->encode($url1);
    $shortUrl2 = $this->urlShortenerService->encode($url2);

    expect($shortUrl1)->not()->toBe($shortUrl2);
});

it('encodes and then decodes a URL successfully', function () {
    $originalUrl = 'https://www.example.com';

    $encodeResponse = $this->postJson(route('encode'), ['url' => $originalUrl]);

    $encodeResponse->assertStatus(200)
        ->assertApiResponseStructure()
        ->assertJsonFragment([
            'message' => 'URL encoded successfully'
        ]);

    $shortUrl = $encodeResponse->json('data.short_url');

    $decodeResponse = $this->postJson(route('decode'), ['short_url' => $shortUrl]);

    $decodeResponse->assertStatus(200)
        ->assertApiResponseStructure()
        ->assertJsonFragment([
            'message' => 'URL decoded successfully',
            'original_url' => $originalUrl
        ]);
});
