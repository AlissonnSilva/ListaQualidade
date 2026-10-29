<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    private const CSRF_TOKEN = 'feature-test-token';

    /** @param array<string, mixed> $data */
    protected function postWithCsrf(string $uri, array $data): TestResponse
    {
        return $this->withSession(['_token' => self::CSRF_TOKEN])
            ->post($uri, $data + ['_token' => self::CSRF_TOKEN]);
    }

    /** @param array<string, mixed> $data */
    protected function putWithCsrf(string $uri, array $data): TestResponse
    {
        return $this->withSession(['_token' => self::CSRF_TOKEN])
            ->put($uri, $data + ['_token' => self::CSRF_TOKEN]);
    }

    protected function deleteWithCsrf(string $uri): TestResponse
    {
        return $this->withSession(['_token' => self::CSRF_TOKEN])
            ->delete($uri, ['_token' => self::CSRF_TOKEN]);
    }
}
