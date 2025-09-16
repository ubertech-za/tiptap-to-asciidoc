<?php

declare(strict_types=1);

namespace UbertechZa\TipTapToAsciiDoc\Converter;

use UbertechZa\TipTapToAsciiDoc\Node;

class DocumentConverter implements ConverterInterface
{
    public function convert(Node $node): string
    {
        // The document node is just a container, return its converted children
        $content = $node->getConvertedContent() ?? '';
        return trim($content);
    }

    /**
     * @return string[]
     */
    public function getSupportedTypes(): array
    {
        return ['doc', 'document'];
    }
}