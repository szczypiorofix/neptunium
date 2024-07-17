<?php

namespace Neptunium\Core;

use Exception;

class Environment {
    private Dotenv $dotenv;
    public function __construct(
        private readonly string $rootDir,
        private readonly string $appRootDir,
    ) {}

    /**
     * @throws Exception
     */
    public function loadDotEnv(array $requiredEnvironmentalVariableKeys = []): void {
        $this->dotenv = new Dotenv();
        $this->dotenv->load(
            $this->rootDir . '/.env',
            $requiredEnvironmentalVariableKeys
        );
    }

    public function getEnvironmentRegisteredKeys(): array {
        return $this->dotenv->getRegisteredKeys();
    }

    public function getRootDir(): string {
        return $this->rootDir;
    }

    public function getAppRootDir(): string {
        return $this->appRootDir;
    }
}
