<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cors extends BaseConfig
{

    public array $default = [

        'allowedOrigins' => [],

        'allowedOriginsPatterns' => [],

        'supportsCredentials' => false,

        'allowedHeaders' => [],

        'exposedHeaders' => [],

        'allowedMethods' => [],

        'maxAge' => 7200,
    ];
}
