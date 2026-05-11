<?php

namespace Config;

use Kint\Parser\ConstructablePluginInterface;
use Kint\Renderer\Rich\TabPluginInterface;
use Kint\Renderer\Rich\ValuePluginInterface;

class Kint
{

    public $plugins;

    public int $maxDepth           = 6;
    public bool $displayCalledFrom = true;
    public bool $expanded          = false;

    public string $richTheme = 'aante-light.css';
    public bool $richFolder  = false;

    public $richObjectPlugins;

    public $richTabPlugins;

    public bool $cliColors      = true;
    public bool $cliForceUTF8   = false;
    public bool $cliDetectWidth = true;
    public int $cliMinWidth     = 40;
}
