<?php

namespace Neptunium\Core;

use Exception;

class Environment {
    public function __construct(
        private readonly string $rootDir,
        private readonly string $appRootDir,
    ) {}

    /**
     * @throws Exception
     */
    public function loadDotEnv(array $requiredEnvironmentalVariableKeys = []): void {
        $dotenv = new Dotenv();
        $dotenv->load(
            $this->rootDir . '/.env',
            $requiredEnvironmentalVariableKeys
        );
    }

    public function getRootDir(): string {
        return $this->rootDir;
    }

    public function getAppRootDir(): string {
        return $this->appRootDir;
    }
}
