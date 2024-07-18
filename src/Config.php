<?php

namespace Neptunium;

class Config {
    public const REQUIRED_ENVIRONMENTAL_VARIABLES = [
        Config::ENV_NEP_DB_NAME,
        Config::ENV_NEP_DB_HOST,
        Config::ENV_NEP_DB_USER,
        Config::ENV_NEP_DB_PASS,
    ];

    public const ENV_NEP_NAME = "NEP_NAME";
    public const ENV_NEP_VAR = "NEP_VER";
    public const ENV_NEP_DB_NAME = "NEP_DB_NAME";
    public const ENV_NEP_DB_HOST = "NEP_DB_HOST";
    public const ENV_NEP_DB_USER = "NEP_DB_USER";
    public const ENV_NEP_DB_PASS = "NEP_DB_PASS";
    public const ENV_NEP_DB_PORT = "NEP_DB_PORT";
    public const ENV_NEP_DB_CHARSET = "NEP_DB_CHARSET";
    public const ENV_NEP_DB_TAB_PREFIX = "NEP_DB_TABLE_PREFIXES";
}
