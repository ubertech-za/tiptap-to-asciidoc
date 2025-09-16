<?php

declare(strict_types=1);

namespace UbertechZa\TipTapToAsciiDoc\Converter;

use UbertechZa\TipTapToAsciiDoc\Node;

class DefaultConverter implements ConverterInterface
{
    public const DEFAULT_CONVERTER = '__DEFAULT__';

    public function convert(Node $node): string
    {
        // For unknown node types, just return their converted content
        $content = $node->getConvertedContent() ?? '';
        
        // If it's a text node without a specific converter, return the text
        if ($content === '' && $node->getText() !== '') {
            return $node->getText();
        }
        
        return $content;
    }

    /**
     * @return string[]
     */
    public function getSupportedTypes(): array
    {
        return [self::DEFAULT_CONVERTER];
    }
}