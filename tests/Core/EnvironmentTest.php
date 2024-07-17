<?php

namespace Neptunium\Tests\Core;

use Exception;
use Neptunium\Core\Environment;
use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase {

    private Environment $environment;

    public function setUp(): void {
        $this->environment = new Environment(
            'C:\workshop\htdocs\neptunium',
            'C:\workshop\htdocs\neptunium\neptunium'
        );
        parent::setUp();
    }

    public function testGetRootDir() {
        $this->assertTrue(strlen($this->environment->getRootDir()) > 0);
    }

    public function testLoadDotEnv() {
        $requiredEnvironmentalVariableKeys = [
            "DB_NAME",
            "DB_HOST",
            "DB_USER",
            "DB_PASS",
        ];
        try {
            $this->environment->loadDotEnv(
                $this->environment->getRootDir() . '/.env',
                $requiredEnvironmentalVariableKeys
            );
        } catch (Exception $e) {
            throw $e;
        }
        $this->assertTrue($this->environment->getDotenv() != null);
    }

    /**
     * @throws Exception
     */
    public function testRegisteredKeys() {
        $this->assertTrue($this->environment->getEnvironmentRegisteredKeys() > 0);
    }
}
