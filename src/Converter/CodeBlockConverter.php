<?php

declare(strict_types=1);

namespace UbertechZa\TipTapToAsciiDoc\Converter;

use UbertechZa\TipTapToAsciiDoc\Node;

class CodeBlockConverter implements ConverterInterface
{
    public function convert(Node $node): string
    {
        $content = $node->getConvertedContent() ?? '';
        $language = $node->getAttr('language', '');
        
        if (trim($content) === '') {
            return '';
        }

        // AsciiDoc code block syntax
        $result = "\n[source";
        if ($language !== '') {
            $result .= ",$language";
        }
        $result .= "]\n----\n";
        $result .= trim($content);
        $result .= "\n----\n\n";

        return $result;
    }

    /**
     * @return string[]
     */
    public function getSupportedTypes(): array
    {
        return ['codeBlock', 'code_block'];
    }
}