<?php

declare(strict_types=1);

namespace UbertechZa\TipTapToAsciiDoc;

use UbertechZa\TipTapToAsciiDoc\Converter\BulletListConverter;
use UbertechZa\TipTapToAsciiDoc\Converter\CodeBlockConverter;
use UbertechZa\TipTapToAsciiDoc\Converter\ConverterInterface;
use UbertechZa\TipTapToAsciiDoc\Converter\DefaultConverter;
use UbertechZa\TipTapToAsciiDoc\Converter\DocumentConverter;
use UbertechZa\TipTapToAsciiDoc\Converter\HeadingConverter;
use UbertechZa\TipTapToAsciiDoc\Converter\ListItemConverter;
use UbertechZa\TipTapToAsciiDoc\Converter\OrderedListConverter;
use UbertechZa\TipTapToAsciiDoc\Converter\ParagraphConverter;
use UbertechZa\TipTapToAsciiDoc\Converter\TextConverter;

final class Environment
{
    /** @var Configuration */
    protected $config;

    /** @var ConverterInterface[] */
    protected $converters = [];

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        $this->config = new Configuration($config);
        $this->addConverter(new DefaultConverter());
    }

    public function getConfig(): Configuration
    {
        return $this->config;
    }

    public function addConverter(ConverterInterface $converter): void
    {
        if ($converter instanceof ConfigurationAwareInterface) {
            $converter->setConfig($this->config);
        }

        foreach ($converter->getSupportedTypes() as $type) {
            $this->converters[$type] = $converter;
        }
    }

    public function getConverterByType(string $type): ConverterInterface
    {
        if (isset($this->converters[$type])) {
            return $this->converters[$type];
        }

        return $this->converters[DefaultConverter::DEFAULT_CONVERTER];
    }

    /**
     * @param array<string, mixed> $config
     */
    public static function createDefaultEnvironment(array $config = []): Environment
    {
        $environment = new static($config);

        $environment->addConverter(new BulletListConverter());
        $environment->addConverter(new CodeBlockConverter());
        $environment->addConverter(new DocumentConverter());
        $environment->addConverter(new HeadingConverter());
        $environment->addConverter(new ListItemConverter());
        $environment->addConverter(new OrderedListConverter());
        $environment->addConverter(new ParagraphConverter());
        $environment->addConverter(new TextConverter());

        return $environment;
    }
}