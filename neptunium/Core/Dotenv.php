<?php

namespace Neptunium\Core;

use Exception;

class Dotenv {
    private string $fileRawContent;
    private array $fileContentArray;
    public function __construct() {
        $this->fileRawContent = "";
        $this->fileContentArray = [];
    }

    /**
     * @throws Exception
     */
    public function load(string $path): void {
        $this->fileRawContent = @file_get_contents($path);
        if (!$this->fileRawContent) {
            throw new Exception('Unable to read file: ' . $path);
        }
        $this->parseContent();
    }

    private function parseContent(): void {
        $this->fileContentArray = explode(PHP_EOL, nl2br($this->fileRawContent));
//        print_r($this->fileContentArray);
    }
}