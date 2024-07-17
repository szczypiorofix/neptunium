<?php

namespace Neptunium\Tests\Core;

use Exception;
use Neptunium\Core\Environment;
use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase {

    public function testGetRootDir() {}

    public function testLoadDotEnv() {}

    /**
     * @throws Exception
     */
    public function testRegisteredKeys() {
        $environment = new Environment(
            'C:\workshop\htdocs\neptunium',
            'C:\workshop\htdocs\neptunium\neptunium'
        );
        try {
            $environment->loadDotEnv();
        } catch (Exception $e) {
            throw $e;
        }
        $this->assertTrue($environment->getEnvironmentRegisteredKeys() > 0);
    }
}
