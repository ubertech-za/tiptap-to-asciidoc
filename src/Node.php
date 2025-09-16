<?php

declare(strict_types=1);

namespace UbertechZa\TipTapToAsciiDoc;

class Node
{
    /** @var array<string, mixed> */
    private $data;

    /** @var string|null */
    private $convertedContent;

    /**
     * @param array<string, mixed> $nodeData
     */
    public function __construct(array $nodeData)
    {
        $this->data = $nodeData;
    }

    public function getType(): string
    {
        return $this->data['type'] ?? 'unknown';
    }

    /**
     * @return array<string, mixed>
     */
    public function getAttrs(): array
    {
        return $this->data['attrs'] ?? [];
    }

    /**
     * @param mixed $default
     * @return mixed
     */
    public function getAttr(string $name, $default = null)
    {
        return $this->getAttrs()[$name] ?? $default;
    }

    /**
     * @return array<mixed>
     */
    public function getContent(): array
    {
        return $this->data['content'] ?? [];
    }

    /**
     * @return array<mixed>
     */
    public function getMarks(): array
    {
        return $this->data['marks'] ?? [];
    }

    public function getText(): string
    {
        return $this->data['text'] ?? '';
    }

    public function hasContent(): bool
    {
        return !empty($this->getContent());
    }

    public function hasMarks(): bool
    {
        return !empty($this->getMarks());
    }

    public function setConvertedContent(string $content): void
    {
        $this->convertedContent = $content;
    }

    public function getConvertedContent(): ?string
    {
        return $this->convertedContent;
    }
}