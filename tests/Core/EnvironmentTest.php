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
        try {
            $this->environment->loadDotEnv($this->environment->getRootDir() . '/.env');
        } catch (Exception $e) {
            throw $e;
        }
        $this->assertTrue($this->environment->getDotenv() != null);
    }

    public function testRequiredEnvironmentalVariables() {
        $requiredEnvironmentalVariableKeys = [
            "DB_NAME",
            "DB_HOST",
            "DB_USER",
            "DB_PASS",
        ];
        try {
            $allVariablesAreAvailable = $this->environment->checkRequiredEnvironmentalVariables($requiredEnvironmentalVariableKeys);
        } catch (Exception $e) {
            print_r($e);
        }
        $this->assertTrue($allVariablesAreAvailable);
    }

    public function testRegisteredKeys() {
        $this->assertTrue($this->environment->getEnvironmentRegisteredKeys() > 0);
    }
}
