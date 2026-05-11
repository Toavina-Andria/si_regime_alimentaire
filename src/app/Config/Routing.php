<?php

namespace Config;

use CodeIgniter\Config\Routing as BaseRouting;

class Routing extends BaseRouting
{

    public array $routeFiles = [
        APPPATH . 'Config/Routes.php',
    ];

    public string $defaultNamespace = 'App\Controllers';

    public string $defaultController = 'Home';

    public string $defaultMethod = 'index';

    public bool $translateURIDashes = false;

    public ?string $override404 = null;

    public bool $autoRoute = false;

    public bool $useControllerAttributes = true;

    public bool $prioritize = false;

    public bool $multipleSegmentsOneParam = false;

    public array $moduleRoutes = [];

    public bool $translateUriToCamelCase = true;
}
