<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;

$config = new Configuration();
return $config
    // Ignore unknown classes for PHP 8.1 compatibility. Remove after dropping PHP 8.1 support.
     ->ignoreUnknownClasses([AllowDynamicProperties::class]);
