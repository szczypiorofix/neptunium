<?php

namespace Neptunium\Tests\Core;

use Exception;
use Neptunium\Config;
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
        $requiredEnvironmentalVariableKeys = Config::REQUIRED_ENVIRONMENTAL_VARIABLES;
        $this->assertTrue($this->environment->checkRequiredEnvironmentalVariables($requiredEnvironmentalVariableKeys));
    }

    public function testRegisteredKeys() {
        $this->assertTrue($this->environment->getEnvironmentRegisteredKeys() > 0);
    }
}
