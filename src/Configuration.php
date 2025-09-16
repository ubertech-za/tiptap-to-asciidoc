<?php

declare(strict_types=1);

namespace UbertechZa\TipTapToAsciiDoc;

class Configuration
{
    /** @var array<string, mixed> */
    private $options;

    /**
     * @param array<string, mixed> $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function merge(array $options): void
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @param mixed $default
     * @return mixed
     */
    public function getOption(string $key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }

    /**
     * @param mixed $value
     */
    public function setOption(string $key, $value): void
    {
        $this->options[$key] = $value;
    }
}