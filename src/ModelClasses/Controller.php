<?php

namespace Neptunium\ModelClasses;

abstract class Controller {
    abstract function index(array $params = []): string;
}
