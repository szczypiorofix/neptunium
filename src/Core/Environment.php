<?php

namespace Neptunium\Core;

use Exception;

class Environment {
    private Dotenv $dotenv;
    public function __construct(
        private readonly string $rootDir,
        private readonly string $appRootDir,
    ) {
        $this->dotenv = new Dotenv();
    }

    /**
     * @throws Exception
     */
    public function loadDotEnv(string $dotEnvFileName, array $requiredEnvironmentalVariableKeys = []): void {
        $this->dotenv->load(
            $dotEnvFileName,
            $requiredEnvironmentalVariableKeys
        );
    }

    public function getEnvironmentRegisteredKeys(): array {
        return $this->dotenv->getRegisteredKeys();
    }

    public function getDotenv(): Dotenv {
        return $this->dotenv;
    }

    public function getRootDir(): string {
        return $this->rootDir;
    }

    public function getAppRootDir(): string {
        return $this->appRootDir;
    }
}
