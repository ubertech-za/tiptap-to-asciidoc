<?php

declare(strict_types=1);

namespace UbertechZa\TipTapToAsciiDoc\Converter;

use UbertechZa\TipTapToAsciiDoc\Node;

class ParagraphConverter implements ConverterInterface
{
    public function convert(Node $node): string
    {
        $content = $node->getConvertedContent() ?? '';
        
        if (trim($content) === '') {
            return '';
        }

        return trim($content) . "\n\n";
    }

    /**
     * @return string[]
     */
    public function getSupportedTypes(): array
    {
        return ['paragraph'];
    }
}