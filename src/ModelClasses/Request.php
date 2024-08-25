<?php

namespace Neptunium\ModelClasses;

class Request {
    private array $headers;
    private string $method;
    private string $url;
    private ?string $body = null;

    public function __construct() {
        $this->url = $this->resolveUrl();
        $this->method = $this->resolveMethod();
        $this->headers = array_change_key_case(getallheaders());
    }

    private function resolveUrl(): string {
        $filteredUrl = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_STRING);
        if ($filteredUrl) {
            return rtrim($filteredUrl);
        }
        return '';
    }

    private function resolveMethod(): string {
        return ($_SERVER["REQUEST_METHOD"]);
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function getUrl(): string {
        return $this->url;
    }

    public function getBody(): string {
        return $this->body;
    }
}
