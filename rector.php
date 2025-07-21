<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83;

$config = RectorConfig::configure()
    ->withPhpSets(php81: true)
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/test',
    ])
    ->withPreparedSets(
        typeDeclarations: true,
    );

return $config;