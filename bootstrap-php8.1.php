<?php

declare(strict_types=1);

if (!class_exists('AllowDynamicProperties')) {
    #[Attribute(Attribute::TARGET_CLASS)]
    class AllowDynamicProperties {}
}