<?php

namespace Neptunium\Core;

use Exception;

class Environment {
    public function __construct(
        private readonly string $rootDir,
        private readonly string $appRootDir,
        private readonly array $requiredEnvironmentalVariables = []
    ) {}

    /**
     * @throws Exception
     */
    public function loadDotEnv(): void {
        $dotenv = new Dotenv();
        $dotenv->load(
            $this->rootDir . '/.env',
            $this->requiredEnvironmentalVariables
        );
    }

    public function getRootDir(): string {
        return $this->rootDir;
    }

    public function getAppRootDir(): string {
        return $this->appRootDir;
    }

    public function getRequiredEnvironmentalVariables(): array {
        return $this->requiredEnvironmentalVariables;
    }
}
