<?php

declare(strict_types=1);

namespace UbertechZa\TipTapToAsciiDoc\Converter;

use UbertechZa\TipTapToAsciiDoc\Node;

class HeadingConverter implements ConverterInterface
{
    public function convert(Node $node): string
    {
        $level = (int) $node->getAttr('level', 1);
        $content = $node->getConvertedContent() ?? '';
        
        if (trim($content) === '') {
            return '';
        }

        // AsciiDoc heading syntax: = Level 1, == Level 2, etc.
        $prefix = str_repeat('=', $level) . ' ';
        
        return $prefix . trim($content) . "\n\n";
    }

    /**
     * @return string[]
     */
    public function getSupportedTypes(): array
    {
        return ['heading'];
    }
}