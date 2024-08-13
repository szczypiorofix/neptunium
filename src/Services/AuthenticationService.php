<?php

namespace Neptunium\Services;

use Neptunium\ModelClasses\BaseService;

class AuthenticationService extends BaseService {

    public function __construct(array $dependencies = []) {
        parent::__construct("auth", $dependencies);
    }

    function initialize(): void {

    }
}
