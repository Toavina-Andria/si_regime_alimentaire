<?php

namespace Config;

class WorkerMode
{

    public array $persistentServices = [
        'autoloader',
        'locator',
        'exceptions',
        'commands',
        'codeigniter',
        'superglobals',
        'routes',
        'cache',
    ];

    public array $resetEventListeners = [];

    public bool $forceGarbageCollection = true;
}
