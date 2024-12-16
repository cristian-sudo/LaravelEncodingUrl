<?php

namespace App\DTO;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UrlDecodeDTO
{
    public string $shortUrl;

    /**
     * UrlDecodeDTO constructor.
     *
     * @param array $data
     * @throws ValidationException
     */
    public function __construct(array $data)
    {
        $validatedData = $this->validate($data);
        $this->shortUrl = $validatedData['short_url'];
    }

    /**
     * Validate the data.
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public function validate(array $data): array
    {
        $validator = Validator::make($data, [
            'short_url' => 'required|url',
        ], [
            'short_url.required' => 'A short URL is required.',
            'short_url.url' => 'The value must be a valid URL.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'short_url' => $this->shortUrl,
        ];
    }
}
