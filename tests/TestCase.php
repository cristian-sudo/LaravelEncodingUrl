<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        TestResponse::macro('assertApiResponseStructure', function () {
            return $this->assertJsonStructure([
                'status',
                'message',
                'data',
                'errors',
                'statusCode'
            ]);
        });
    }
}
