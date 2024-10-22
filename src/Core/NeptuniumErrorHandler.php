<?php

/*
 * The MIT License
 *
 * Copyright 2024 szczypiorofix.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Neptunium\Core;

/**
 * Description of NeptuniumErrorHandler
 *
 * @author szczypiorofix
 */
class NeptuniumErrorHandler {
    private mixed $previousErrorHandler = null;
    private static ?NeptuniumErrorHandler $errorHandler = null;
    
    private function __construct() {
        $this->previousErrorHandler = set_error_handler([$this, "neptuniumErrorHandler"]);
    }
    
    private function __clone() {}
    
    public static function getInstance(): NeptuniumErrorHandler {
        if (is_null(self::$errorHandler)) {
            self::$errorHandler = new NeptuniumErrorHandler();
        }
        return self::$errorHandler;
    }
    
    public function neptuniumErrorHandler($errno, $errstr, $errfile, $errline): bool {
        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting, so let it fall
            // through to the standard PHP error handler
            return false;
        }

        // $errstr may need to be escaped:
        $_errstr = htmlspecialchars($errstr);

        switch ($errno) {
            case E_USER_ERROR:
                echo "<b>My ERROR</b> [$errno] $_errstr<br />\n";
                echo "  Fatal error on line $errline in file $errfile";
                echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
                echo "Aborting...<br />\n";
                exit(1);

            case E_USER_WARNING:
                echo "<b>My WARNING</b> [$errno] $_errstr<br />\n";
                break;

            case E_USER_NOTICE:
                echo "<b>My NOTICE</b> [$errno] $_errstr<br />\n";
                break;

            default:
                echo "Unknown error type: [$errno] $_errstr<br />\n";
                break;
        }

        /* Don't execute PHP internal error handler */
        return true;
    }
}
