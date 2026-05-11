<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class CURLRequest extends BaseConfig
{

    public array $shareConnectionOptions = [
        CURL_LOCK_DATA_CONNECT,
        CURL_LOCK_DATA_DNS,
    ];

    public bool $shareOptions = false;
}
