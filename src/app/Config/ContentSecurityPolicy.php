<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class ContentSecurityPolicy extends BaseConfig
{

    public bool $reportOnly = false;

    public ?string $reportURI = null;

    public ?string $reportTo = null;

    public bool $upgradeInsecureRequests = false;

    public $defaultSrc;

    public $scriptSrc = 'self';

    public array|string $scriptSrcElem = 'self';

    public array|string $scriptSrcAttr = 'self';

    public $styleSrc = 'self';

    public array|string $styleSrcElem = 'self';

    public array|string $styleSrcAttr = 'self';

    public $imageSrc = 'self';

    public $baseURI;

    public $childSrc = 'self';

    public $connectSrc = 'self';

    public $fontSrc;

    public $formAction = 'self';

    public $frameAncestors;

    public $frameSrc;

    public $mediaSrc;

    public $objectSrc = 'self';

    public $manifestSrc;

    public array|string $workerSrc = [];

    public $pluginTypes;

    public $sandbox;

    public string $styleNonceTag = '{csp-style-nonce}';

    public string $scriptNonceTag = '{csp-script-nonce}';

    public bool $autoNonce = true;
}
