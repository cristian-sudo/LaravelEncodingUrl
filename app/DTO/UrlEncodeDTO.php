<?php

namespace App\DTO;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UrlEncodeDTO
{
    public string $url;

    /**
     * UrlEncodeDTO constructor.
     *
     * @param array $data
     * @throws ValidationException
     */
    public function __construct(array $data)
    {
        $validatedData = $this->validate($data);
        $this->url = $validatedData['url'];
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
            'url' => 'required|url',
        ], [
            'url.required' => 'A URL is required.',
            'url.url' => 'The value must be a valid URL.',
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
            'url' => $this->url,
        ];
    }
}
