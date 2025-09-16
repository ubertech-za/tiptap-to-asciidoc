<?php

declare(strict_types=1);

namespace UbertechZa\TipTapToAsciiDoc\Converter;

use UbertechZa\TipTapToAsciiDoc\Node;

interface ConverterInterface
{
    public function convert(Node $node): string;

    /**
     * @return string[]
     */
    public function getSupportedTypes(): array;
}