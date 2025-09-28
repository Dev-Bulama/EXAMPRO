<?php

declare(strict_types=1);

namespace App\Support;

final class Response
{
    public function __construct(
        private int $status = 200,
        private array $headers = [],
        private mixed $data = null
    ) {
    }

    public static function json(array $data, int $status = 200): self
    {
        return new self($status, ['Content-Type' => 'application/json'], $data);
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $header => $value) {
            header(sprintf('%s: %s', $header, $value));
        }
        if (is_array($this->data) || is_object($this->data)) {
            echo json_encode($this->data, JSON_THROW_ON_ERROR);
        } else {
            echo (string) $this->data;
        }
    }
}
