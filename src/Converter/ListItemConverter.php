<?php

declare(strict_types=1);

namespace UbertechZa\TipTapToAsciiDoc\Converter;

use UbertechZa\TipTapToAsciiDoc\Node;

class ListItemConverter implements ConverterInterface
{
    public function convert(Node $node): string
    {
        $content = $node->getConvertedContent() ?? '';
        
        if (trim($content) === '') {
            return '';
        }

        // Determine list type from parent context
        // For now, default to bullet list style
        $marker = $this->getListMarker($node);
        
        // Remove paragraph wrapping from list items (common in Tiptap)
        $content = $this->cleanParagraphWrapping($content);
        
        return $marker . ' ' . trim($content) . "\n";
    }

    private function getListMarker(Node $node): string
    {
        // This would ideally check the parent list type
        // For now, we'll use bullet list style
        // TODO: Implement context awareness for numbered vs bullet lists
        return '*';
    }

    private function cleanParagraphWrapping(string $content): string
    {
        // Remove excessive newlines that come from paragraph wrapping
        $content = trim($content);
        $content = preg_replace('/\n\n+/', "\n", $content);
        
        return $content;
    }

    /**
     * @return string[]
     */
    public function getSupportedTypes(): array
    {
        return ['listItem', 'list_item'];
    }
}