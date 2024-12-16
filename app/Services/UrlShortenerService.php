<?php

namespace App\Services;

use App\Interfaces\UrlShortenerInterface;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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
     * Generate a unique alphanumeric code for URL shortening.
     *
     * This method generates a Universally Unique Identifier (UUID) and then
     * creates an MD5 hash of the UUID. The hash is truncated to a specified
     * length to produce a shortened alphanumeric code. This approach
     * minimizes the risk of collisions by leveraging the uniqueness of UUIDs.
     *
     * @return string The generated alphanumeric code.
     *
     * @throws Exception If it was not possible to gather sufficient entropy.
     */
    protected function generateCode(): string
    {
        $uuid = Str::uuid()->toString();
        return substr(md5($uuid), 0, self::CODE_LENGTH);
    }
}
