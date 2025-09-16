<?php

declare(strict_types=1);

namespace UbertechZa\TipTapToAsciiDoc\Core;

class AsciiDocSerializer
{
    protected $document;
    protected $schema;
    protected $configuration;

    public function __construct($schema = null, array $configuration = [])
    {
        $this->schema = $schema;
        $this->configuration = array_merge([
            'blockSeparator' => "\n\n",
        ], $configuration);
    }

    public function process(array $value): string
    {
        $asciidoc = [];

        // Transform document to object
        $this->document = json_decode(json_encode($value));

        $content = is_array($this->document->content) ? $this->document->content : [];

        foreach ($content as $node) {
            $result = $this->renderNode($node);
            if ($result !== '') {
                $asciidoc[] = $result;
            }
        }

        $result = implode('', $asciidoc);
        
        return $this->sanitize($result);
    }

    public function renderNode($node): string
    {
        // Handle text nodes with marks
        if (isset($node->text)) {
            return $this->renderTextNode($node);
        }

        $nodeType = $node->type ?? 'unknown';

        // Handle different node types
        switch ($nodeType) {
            case 'doc':
            case 'document':
                return $this->renderDocument($node);
            case 'heading':
                return $this->renderHeading($node);
            case 'paragraph':
                return $this->renderParagraph($node);
            case 'bulletList':
            case 'bullet_list':
                return $this->renderBulletList($node);
            case 'orderedList':
            case 'ordered_list':
                return $this->renderOrderedList($node);
            case 'listItem':
            case 'list_item':
                return $this->renderListItem($node);
            case 'codeBlock':
            case 'code_block':
                return $this->renderCodeBlock($node);
            case 'blockquote':
                return $this->renderBlockquote($node);
            case 'hardBreak':
            case 'hard_break':
                return $this->renderHardBreak($node);
            case 'table':
                return $this->renderTable($node);
            case 'tableRow':
            case 'table_row':
                return $this->renderTableRow($node);
            case 'tableCell':
            case 'table_cell':
                return $this->renderTableCell($node);
            case 'tableHeader':
            case 'table_header':
                return $this->renderTableHeader($node);
            default:
                // Fallback: render child content
                return $this->renderChildContent($node);
        }
    }

    private function renderTextNode($node): string
    {
        $text = $node->text ?? '';
        
        if (!isset($node->marks) || empty($node->marks)) {
            return $text;
        }

        // Sort marks by priority for proper nesting in AsciiDoc
        // Bold (*) should be outermost, then italic (_), then code (`)
        $marks = (array) $node->marks;
        usort($marks, function($a, $b) {
            $priority = [
                'code' => 1,      // Innermost
                'italic' => 2,
                'em' => 2,
                'bold' => 3,      // Outermost  
                'strong' => 3,
                'link' => 4,      // Links wrap everything
            ];
            
            $aType = $a->type ?? '';
            $bType = $b->type ?? '';
            
            $aPriority = $priority[$aType] ?? 5;
            $bPriority = $priority[$bType] ?? 5;
            
            return $aPriority - $bPriority;
        });

        // Apply marks to text in correct order
        foreach ($marks as $mark) {
            $text = $this->applyMark($mark, $text);
        }

        return $text;
    }

    private function applyMark($mark, string $text): string
    {
        $markType = $mark->type ?? '';
        
        switch ($markType) {
            case 'bold':
            case 'strong':
                return '*' . $text . '*';
            case 'italic':
            case 'em':
                return '_' . $text . '_';
            case 'code':
                return '`' . $text . '`';
            case 'link':
                $href = $mark->attrs->href ?? '';
                if ($href !== '') {
                    return $this->formatLink($href, $text);
                }
                return $text;
            default:
                return $text;
        }
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

    private function renderDocument($node): string
    {
        return $this->renderChildContent($node);
    }

    private function renderHeading($node): string
    {
        $level = $node->attrs->level ?? 1;
        $content = $this->renderChildContent($node);
        
        if (trim($content) === '') {
            return '';
        }

        $prefix = str_repeat('=', $level) . ' ';
        return $prefix . trim($content) . "\n\n";
    }

    private function renderParagraph($node): string
    {
        $content = $this->renderChildContent($node);
        
        if (trim($content) === '') {
            return '';
        }

        return trim($content) . "\n\n";
    }

    private function renderBulletList($node): string
    {
        if (!isset($node->content) || !is_array($node->content)) {
            return '';
        }

        $content = '';
        foreach ($node->content as $listItem) {
            if (($listItem->type ?? '') === 'listItem' || ($listItem->type ?? '') === 'list_item') {
                $itemContent = $this->renderListItemContent($listItem);
                if ($itemContent !== '') {
                    $content .= "* " . trim($itemContent) . "\n";
                }
            }
        }

        return $content !== '' ? "\n" . $content . "\n" : '';
    }

    private function renderOrderedList($node): string
    {
        if (!isset($node->content) || !is_array($node->content)) {
            return '';
        }

        $content = '';
        foreach ($node->content as $listItem) {
            if (($listItem->type ?? '') === 'listItem' || ($listItem->type ?? '') === 'list_item') {
                $itemContent = $this->renderListItemContent($listItem);
                if ($itemContent !== '') {
                    $content .= ". " . trim($itemContent) . "\n";
                }
            }
        }

        return $content !== '' ? "\n" . $content . "\n" : '';
    }

    private function renderListItem($node): string
    {
        $content = $this->renderChildContent($node);
        
        if (trim($content) === '') {
            return '';
        }

        // TODO: Determine if this is in an ordered or bullet list
        // For now, default to bullet list
        $marker = '*';
        
        // Clean paragraph wrapping
        $content = trim($content);
        $content = preg_replace('/\n\n+/', "\n", $content);
        
        return $marker . ' ' . $content . "\n";
    }

    private function renderListItemContent($node): string
    {
        if (!isset($node->content) || !is_array($node->content)) {
            return '';
        }

        $content = '';
        foreach ($node->content as $childNode) {
            $nodeContent = $this->renderNode($childNode);
            if ($nodeContent !== '') {
                $content .= $nodeContent;
            }
        }

        // Clean paragraph wrapping and remove extra newlines
        $content = trim($content);
        $content = preg_replace('/\n\n+/', " ", $content);
        
        return $content;
    }

    private function renderCodeBlock($node): string
    {
        $content = $this->renderChildContent($node);
        $language = $node->attrs->language ?? '';
        
        if (trim($content) === '') {
            return '';
        }

        $result = "\n[source";
        if ($language !== '') {
            $result .= ",$language";
        }
        $result .= "]\n----\n";
        $result .= trim($content);
        $result .= "\n----\n\n";

        return $result;
    }

    private function renderBlockquote($node): string
    {
        $content = $this->renderChildContent($node);
        
        if (trim($content) === '') {
            return '';
        }

        // Add quote block syntax
        return "\n[quote]\n____\n" . trim($content) . "\n____\n\n";
    }

    private function renderHardBreak($node): string
    {
        return "\n";
    }

    private function renderTable($node): string
    {
        if (!isset($node->content) || !is_array($node->content)) {
            return '';
        }

        $tableRows = [];
        $isFirstRow = true;

        foreach ($node->content as $childNode) {
            if (($childNode->type ?? '') === 'tableRow' || ($childNode->type ?? '') === 'table_row') {
                $tableRows[] = $this->renderTableRow($childNode, $isFirstRow);
                $isFirstRow = false;
            }
        }

        if (empty($tableRows)) {
            return '';
        }

        return "\n|===\n" . implode("\n\n", $tableRows) . "\n|===\n\n";
    }


    private function renderTableRow($node, bool $isHeaderRow = false): string
    {
        if (!isset($node->content) || !is_array($node->content)) {
            return '';
        }

        $cells = [];
        foreach ($node->content as $childNode) {
            $cellType = $childNode->type ?? '';
            if ($cellType === 'tableCell' || $cellType === 'table_cell' || 
                $cellType === 'tableHeader' || $cellType === 'table_header') {
                $cells[] = $this->renderTableCell($childNode);
            }
        }

        if ($isHeaderRow) {
            // Header row: all cells on one line separated by |
            return implode('', $cells);
        } else {
            // Data rows: each cell on its own line
            return implode("\n", $cells);
        }
    }

    private function renderTableCell($node): string
    {
        $content = $this->renderChildContent($node);
        
        // Check if cell contains AsciiDoc markup (lists, formatting, etc.)
        $hasAsciiDocMarkup = $this->cellContainsAsciiDocMarkup($node);
        
        // Handle colspan and rowspan attributes
        $spanPrefix = '';
        $attrs = $node->attrs ?? null;
        if ($attrs) {
            $colspan = $attrs->colspan ?? 1;
            $rowspan = $attrs->rowspan ?? 1;
            
            if ($colspan > 1 || $rowspan > 1) {
                $spanPrefix = $colspan . '+';
                if ($rowspan > 1) {
                    $spanPrefix = $rowspan . '.' . $spanPrefix;
                }
            }
        }
        
        // Build proper cell prefix: span + AsciiDoc indicator + cell separator
        if ($hasAsciiDocMarkup) {
            $prefix = $spanPrefix . 'a|';
        } else {
            $prefix = $spanPrefix . '|';
        }
        
        $content = trim($content);
        
        // If cell has AsciiDoc markup (like lists), ensure content starts on new line
        if ($hasAsciiDocMarkup) {
            $content = "\n" . $content;
        } else {
            $content = " " . $content;
        }
        
        return $prefix . $content;
    }

    private function cellContainsAsciiDocMarkup($node): bool
    {
        if (!isset($node->content) || !is_array($node->content)) {
            return false;
        }

        // Check for lists, blockquotes, code blocks, etc.
        foreach ($node->content as $childNode) {
            $type = $childNode->type ?? '';
            if (in_array($type, ['bulletList', 'orderedList', 'blockquote', 'codeBlock', 'bullet_list', 'ordered_list', 'code_block'])) {
                return true;
            }
            
            // Recursively check nested content
            if ($this->cellContainsAsciiDocMarkup($childNode)) {
                return true;
            }
        }
        
        return false;
    }

    private function renderTableHeader($node): string
    {
        // Table headers are handled the same as cells in AsciiDoc
        // The distinction is made by position (first row)
        return $this->renderTableCell($node);
    }

    private function renderChildContent($node): string
    {
        if (!isset($node->content) || !is_array($node->content)) {
            return '';
        }

        $content = '';
        foreach ($node->content as $childNode) {
            $content .= $this->renderNode($childNode);
        }

        return $content;
    }

    private function sanitize(string $asciidoc): string
    {
        // Clean up excessive whitespace and newlines
        $asciidoc = trim($asciidoc, "\n\r\0\x0B");
        
        // Remove multiple consecutive newlines (more than 2)
        $asciidoc = preg_replace('/\n{3,}/', "\n\n", $asciidoc);
        assert($asciidoc !== null);
        
        // Remove leading spaces from each line (indentation cleanup)
        $asciidoc = preg_replace('/^[ \t]+/m', '', $asciidoc);
        assert($asciidoc !== null);
        
        // Remove trailing spaces from each line
        $asciidoc = preg_replace('/[ \t]+$/m', '', $asciidoc);
        assert($asciidoc !== null);

        return $asciidoc;
    }
}