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
    public function loadDotEnv(string $dotEnvFileName): void {
        $this->dotenv->load($dotEnvFileName);
    }

    public function getEnvironmentRegisteredKeys(): array {
        return $this->dotenv->getRegisteredKeys();
    }

    public function checkRequiredEnvironmentalVariables(array $requiredEnvironmentalVariableKeys): bool {
        $allEnvironmentalVariablesAllAvailable = true;
        foreach($requiredEnvironmentalVariableKeys as $requiredEnvironmentalVariable) {
            if (getenv($requiredEnvironmentalVariable) === false) {
                $allEnvironmentalVariablesAllAvailable = false;
            }
        }
        return $allEnvironmentalVariablesAllAvailable;
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
