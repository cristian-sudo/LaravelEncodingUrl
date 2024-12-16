# URL Shortening Service

This project implements a URL shortening service using PHP and Laravel. It provides endpoints to encode long URLs into short URLs and decode short URLs back to their original form.

## Features

- Encode long URLs into short, unique URLs.
- Decode short URLs back to their original URLs.
- In-memory storage of URL mappings using Laravel's caching system.
- Structured JSON responses for all API endpoints.
- Includes a Makefile for running tests and checking code standards.

## Requirements

- PHP 8.3 or higher
- Composer
- Laravel 11.x
- Laravel Valet for local development

## Installation

1. **Clone the Repository**:

   ```bash
   git clone git@github.com:cristian-sudo/LaravelEncodingUrl.git
   cd LaravelEncodingUrl
   ```

2. **Install Dependencies**:

   Use Composer to install the required PHP dependencies:

   ```bash
   composer install
   ```

3. **Environment Setup**:

   Copy the `.env.example` file to `.env` and configure your environment variables as needed:

   ```bash
   cp .env.example .env
   ```

   Generate an application key:

   ```bash
   php artisan key:generate
   ```

4. **Serve with Laravel Valet**:

   If you're using Laravel Valet for local development, navigate to your project directory and link it with Valet:

   ```bash
   valet link laravel-url-shortener
   valet secure laravel-url-shortener
   ```

   Your application will be accessible at `https://laravel-url-shortener.test`.

## Usage

### Encode URL

- **Endpoint**: `/api/encode`
- **Method**: POST
- **Request Body**: JSON with a `url` field containing the long URL to be shortened.

**Example Request**:

```json
{
  "url": "https://www.thisisalongdomain.com/with/some/parameters?and=here_too"
}
```

**Example Response**:

```json
{
  "status": "success",
  "message": "URL encoded successfully",
  "data": {
    "short_url": "https://laravel-url-shortener.test/GeAi9K"
  },
  "errors": [],
  "statusCode": 200
}
```

### Decode URL

- **Endpoint**: `/api/decode`
- **Method**: POST
- **Request Body**: JSON with a `short_url` field containing the short URL to be decoded.

**Example Request**:

```json
{
  "short_url": "https://laravel-url-shortener.test/GeAi9K"
}
```

**Example Response**:

```json
{
  "status": "success",
  "message": "URL decoded successfully",
  "data": {
    "original_url": "https://www.thisisalongdomain.com/with/some/parameters?and=here_too"
  },
  "errors": [],
  "statusCode": 200
}
```

## Testing and Code Quality

The project includes a Makefile to facilitate testing and code quality checks using PHP_CodeSniffer.

### Run Tests

To run the tests, use the following command:

```bash
make test
```

### Code Quality Checks

- **Check Code Standards**:

  Run PHP_CodeSniffer to check code standards:

  ```bash
  make phpcs
  ```

- **Fix Code Standards**:

  Run PHP_CodeBeautifier and Fixer to automatically fix code standards issues:

  ```bash
  make phpcbf
  ```

- **Fix and Check**:

  Run both the fixer and the checker:

  ```bash
  make fix-and-check
  ```

## License

This project is open-source and available under the [MIT License](LICENSE).

Here is a link to the project documentation: [LaravelEncodingUrl](https://docs.google.com/document/d/1fEJT_4ULMYZtKE7lCndNkZ7lMfPdQfHYXHzbXN-H_wE/edit?usp=sharing)
