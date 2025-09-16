<?php

declare(strict_types=1);

namespace UbertechZa\TipTapToAsciiDoc\Converter;

use UbertechZa\TipTapToAsciiDoc\Node;

class BulletListConverter implements ConverterInterface
{
    public function convert(Node $node): string
    {
        $content = $node->getConvertedContent() ?? '';
        
        if (trim($content) === '') {
            return '';
        }

        return "\n" . trim($content) . "\n\n";
    }

    /**
     * @return string[]
     */
    public function getSupportedTypes(): array
    {
        return ['bulletList', 'bullet_list'];
    }
}