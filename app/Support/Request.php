<?php

declare(strict_types=1);

namespace App\Support;

final class Request
{
    private array $attributes = [];

    public function __construct(
        private readonly array $get,
        private readonly array $post,
        private readonly array $server,
        private readonly array $files,
        private readonly array $cookies,
        private readonly array $body
    ) {
    }

    public static function capture(): self
    {
        $body = json_decode(file_get_contents('php://input'), true);
        return new self($_GET, $_POST, $_SERVER, $_FILES, $_COOKIE, is_array($body) ? $body : []);
    }

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function path(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH);
        return $path ?: '/';
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->get, $this->post, $this->body);
    }

    public function header(string $key, mixed $default = null): mixed
    {
        $headerKey = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $this->server[$headerKey] ?? $default;
    }

    public function setAttribute(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function getAttribute(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }
}
