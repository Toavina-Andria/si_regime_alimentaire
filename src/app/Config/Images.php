<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Images\Handlers\GDHandler;
use CodeIgniter\Images\Handlers\ImageMagickHandler;

class Images extends BaseConfig
{

    public string $defaultHandler = 'gd';

    public string $libraryPath = '/usr/local/bin/convert';

    public array $handlers = [
        'gd'      => GDHandler::class,
        'imagick' => ImageMagickHandler::class,
    ];
}
