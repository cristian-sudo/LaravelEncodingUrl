<?php

namespace App\Services;

use App\Interfaces\UrlShortenerInterface;
use Illuminate\Support\Facades\Cache;

class UrlShortenerService implements UrlShortenerInterface
{
    protected const CACHE_PREFIX = 'url_shortener:';
    protected const CODE_LENGTH = 6;
    protected const CACHE_TTL = 60 * 60 * 24 * 30;

    /**
     * Encode a given URL into a unique shortened URL code.
     *
     * This method first checks if the URL has already been encoded and cached.
     * If it has, it returns the existing shortened URL. If not, it generates
     * a new unique code, caches the mapping between the code and the original
     * URL, and returns the shortened URL.
     *
     * @param string $url The original URL to be encoded.
     *
     * @return string The shortened URL.
     */
    public function encode(string $url): string
    {

        $existingCode = Cache::get(self::CACHE_PREFIX . 'code:' . md5($url));
        if ($existingCode) {
            return url('/' . $existingCode);
        }

        do {
            $code = $this->generateCode();
        } while (Cache::has(self::CACHE_PREFIX . 'url:' . $code));

        Cache::put(self::CACHE_PREFIX . 'url:' . $code, $url, self::CACHE_TTL);
        Cache::put(self::CACHE_PREFIX . 'code:' . md5($url), $code, self::CACHE_TTL);

        return url('/' . $code);
    }

    /**
     * Decode a shortened URL code back to its original URL.
     *
     * This method extracts the code from the shortened URL and retrieves
     * the original URL from the cache. If the code does not exist in the cache,
     * it returns an empty string.
     *
     * @param string $shortUrl The shortened URL to be decoded.
     *
     * @return string The original URL if found, otherwise an empty string.
     */
    public function decode(string $shortUrl): string
    {
        $code = str_replace(url('/') . '/', '', $shortUrl);
        return Cache::get(self::CACHE_PREFIX . 'url:' . $code, '');
    }

    /**
     * Generate a random alphanumeric code for URL shortening.
     *
     * This method creates a random string of a specified length using
     * a mix of numbers and both lowercase and uppercase letters. The
     * generated code is used as the unique identifier for the shortened URL.
     *
     * @return string The generated alphanumeric code.
     */
    protected function generateCode(): string
    {
        return substr(
            str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),
            0,
            self::CODE_LENGTH
        );
    }
}
