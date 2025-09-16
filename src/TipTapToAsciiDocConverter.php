<?php

declare(strict_types=1);

namespace UbertechZa\TipTapToAsciiDoc;

use Tiptap\Editor;
use UbertechZa\TipTapToAsciiDoc\Contracts\Converter;
use UbertechZa\TipTapToAsciiDoc\Core\AsciiDocSerializer;

class TipTapToAsciiDocConverter implements Converter
{
    /** @var Editor */
    private $editor;

    /** @var AsciiDocSerializer */
    private $serializer;

    /**
     * @param array<string, mixed> $options
     */
    public function __construct(array $options = [])
    {
        // Create Tiptap Editor with default extensions
        $this->editor = new Editor();
        $this->serializer = new AsciiDocSerializer(null, $options);
    }

    /**
     * @param mixed $input
     */
    public function convert($input): string
    {
        // Set content in the Tiptap editor to parse and validate
        $this->editor->setContent($input);
        
        // Get the parsed document structure
        $document = $this->editor->getDocument();
        
        // Convert to AsciiDoc using our serializer
        return $this->serializer->process($document);
    }

    /**
     * Get the underlying Tiptap Editor instance
     */
    public function getEditor(): Editor
    {
        return $this->editor;
    }

    /**
     * Get the AsciiDoc serializer instance
     */
    public function getSerializer(): AsciiDocSerializer
    {
        return $this->serializer;
    }
}
