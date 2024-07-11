<?php

namespace Neptunium\Core;

class Environment {
    private string $rootDir;
    private string $appRootDir;

    public function __construct(string $rootDir, string $appRootDir) {
        $this->rootDir = $rootDir;
        $this->appRootDir = $appRootDir;
    }

    public function getRootDir(): string {
        return $this->rootDir;
    }

    public function getAppRootDir(): string {
        return $this->appRootDir;
    }
}