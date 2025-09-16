<?php

declare(strict_types=1);

namespace UbertechZa\TipTapToAsciiDoc;

interface ConfigurationAwareInterface
{
    public function setConfig(Configuration $config): void;
}