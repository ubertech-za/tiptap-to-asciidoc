<?php

declare(strict_types=1);

namespace UbertechZa\TipTapToAsciiDoc\Extensions\Nodes;

use Tiptap\Nodes\Document as BaseDocument;

class Document extends BaseDocument
{
    public function renderAsciiDoc($node, array $options = []): string
    {
        $serializer = $options['serializer'];
        
        // Document is just a container, render its children
        $content = '';
        if (isset($node->content) && is_array($node->content)) {
            foreach ($node->content as $childNode) {
                $content .= $serializer->renderNode($childNode);
            }
        }
        
        return trim($content);
    }
}