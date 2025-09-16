<?php

declare(strict_types=1);

namespace UbertechZa\TipTapToAsciiDoc\Converter;

use UbertechZa\TipTapToAsciiDoc\Node;

class TextConverter implements ConverterInterface
{
    public function convert(Node $node): string
    {
        $text = $node->getText();
        
        if ($text === '') {
            return '';
        }

        // Apply marks to the text (bold, italic, etc.)
        return $this->applyMarks($text, $node->getMarks());
    }

    /**
     * @param array<mixed> $marks
     */
    private function applyMarks(string $text, array $marks): string
    {
        if (empty($marks)) {
            return $text;
        }

        foreach ($marks as $mark) {
            $markType = $mark['type'] ?? '';
            
            switch ($markType) {
                case 'bold':
                case 'strong':
                    $text = '*' . $text . '*';
                    break;
                case 'italic':
                case 'em':
                    $text = '_' . $text . '_';
                    break;
                case 'code':
                    $text = '`' . $text . '`';
                    break;
                case 'link':
                    $href = $mark['attrs']['href'] ?? '';
                    if ($href !== '') {
                        $text = $this->formatLink($href, $text);
                    }
                    break;
            }
        }

        return $text;
    }

    private function formatLink(string $href, string $text): string
    {
        // Handle fragment identifiers (anchor links)
        if (strpos($href, '#') === 0) {
            $anchor = substr($href, 1);
            if ($text === $href || $text === $anchor) {
                return '<<' . $anchor . '>>';
            }
            return '<<' . $anchor . ',' . $text . '>>';
        }

        // Handle absolute URLs
        if (preg_match('/^https?:\/\//', $href)) {
            return $href . '[' . $text . ']';
        }

        // Handle relative URLs
        return 'link:' . $href . '[' . $text . ']';
    }

    /**
     * @return string[]
     */
    public function getSupportedTypes(): array
    {
        return ['text'];
    }
}