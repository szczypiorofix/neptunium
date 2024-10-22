<?php

namespace Neptunium\Core;

use Exception;

class Environment {
    private Dotenv $dotenv;
    public function __construct(
        private readonly string $rootDir,
        private readonly string $appRootDir,
    ) {
        $this->dotenv = Dotenv::getInstance();
    }

    /**
     * @throws Exception
     */
    public function loadDotEnv(string $dotEnvFileName): void {
        $this->dotenv->load($dotEnvFileName);
    }

    public function getEnvironmentRegisteredKeys(): array {
        return $this->dotenv->getRegisteredKeys();
    }

    public function checkRequiredEnvironmentalVariables(array $requiredEnvironmentalVariableKeys): bool {
        foreach($requiredEnvironmentalVariableKeys as $requiredEnvironmentalVariable) {
            if (!getenv($requiredEnvironmentalVariable)) {
                return false;
            }
        }
        return true;
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

    public function getEnvValue(string $key): string | null {
        return $this->dotenv->getValue($key);
    }
}
