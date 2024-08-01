<?php

namespace Neptunium\ModelClasses;

class Response {
    private array $headers;

    private int $statusCode;

    private string $statusText;

    private string $content;

    public function __construct() {
        $this->headers = [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Connection' => 'close',

            /**
            * 'Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT',
            * 'Last-Modified' => gmdate( 'D, d M Y H:i:s' ) . ' GMT',
            * 'Cache-Control' => 'no-store, no-cache, must-revalidate',
            * 'Pragma' => 'no-cache'
            */
        ];
        $this->statusCode = 200;
        $this->statusText = 'OK';
        $this->content = '';
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    public function setHeaders(array $headers): void {
        foreach($headers as $headerKey => $headerValue) {
            $this->headers[$headerKey] = $headerValue;
        }
    }

    public function getStatusCode(): int {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void {
        $this->statusCode = $statusCode;
    }

    public function getStatusText(): string {
        return $this->statusText;
    }

    public function setStatusText(string $statusText): void {
        $this->statusText = $statusText;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function setContent(string $content): void {
        $this->content = $content;
    }

    public function viewPageContent(): void {
        $this->prepareHeaders();

        echo $this->content;
    }

    private function prepareHeaders(): void {
        foreach ($this->headers as $header => $value) {
            header("$header: $value");
        }

        echo '<pre>';
        var_dump($this->headers);
        echo '</pre>';

    }
}
