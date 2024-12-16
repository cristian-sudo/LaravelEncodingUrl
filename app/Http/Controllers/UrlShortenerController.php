<?php

namespace App\Http\Controllers;

use App\DTO\UrlEncodeDTO;
use App\DTO\UrlDecodeDTO;
use App\Interfaces\UrlShortenerInterface;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UrlShortenerController extends Controller
{
    use ApiResponse;

    protected UrlShortenerInterface $urlShortener;

    public function __construct(UrlShortenerInterface $urlShortener)
    {
        $this->urlShortener = $urlShortener;
    }

    /**
     * Encode a given URL into a shortened URL.
     *
     * @param Request $request The HTTP request containing the URL to be encoded.
     *
     * @return JsonResponse A JSON response containing the shortened URL on success,
     *                      or an error message and status code on failure.
     *
     * @throws Exception For any other exceptions that occur during encoding.
     */
    public function encode(Request $request): JsonResponse
    {
        try {
            $dto = new UrlEncodeDTO($request->all());
            $shortUrl = $this->urlShortener->encode($dto->url);
            return $this->successResponse(['short_url' => $shortUrl], 'URL encoded successfully');
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (Exception $e) {
            return $this->errorResponse('Failed to encode URL', 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * Decode a shortened URL back to its original URL.
     *
     * @param Request $request The HTTP request containing the shortened URL to be decoded.
     *
     * @return JsonResponse A JSON response containing the original URL on success,
     *                      or an error message and status code if the operation fails.
     *
     * @throws Exception For any other exceptions that occur during decoding.
     */
    public function decode(Request $request): JsonResponse
    {
        try {
            $dto = new UrlDecodeDTO($request->all());
            $originalUrl = $this->urlShortener->decode($dto->shortUrl);
            if ($originalUrl) {
                return $this->successResponse(['original_url' => $originalUrl], 'URL decoded successfully');
            } else {
                return $this->errorResponse('Short URL not found', 404, []);
            }
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (Exception $e) {
            return $this->errorResponse('Failed to decode URL', 500, ['exception' => $e->getMessage()]);
        }
    }
}
