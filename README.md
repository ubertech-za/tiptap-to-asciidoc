# TipTap to AsciiDoc Converter

> ⚠️ **BETA SOFTWARE NOTICE**
> This package is currently in beta and is being prepared for testing in upcoming projects. Please expect possible breaking changes in future releases. We do not recommend using this package in production environments without thorough testing.

A PHP package that converts TipTap JSON to AsciiDoc markup, inspired by the architectural patterns from [TipTap HTML converter](https://github.com/ueberdosis/tiptap). This package enables seamless conversion from rich text editor content (TipTap) to structured documentation format (AsciiDoc).

[![Tests](https://github.com/ubertech-za/tiptap-to-asciidoc/workflows/tests/badge.svg)](https://github.com/ubertech-za/tiptap-to-asciidoc/actions)
[![Latest Stable Version](https://poser.pugx.org/ubertech-za/tiptap-to-asciidoc/v/stable)](https://packagist.org/packages/ubertech-za/tiptap-to-asciidoc)
[![Total Downloads](https://poser.pugx.org/ubertech-za/tiptap-to-asciidoc/downloads)](https://packagist.org/packages/ubertech-za/tiptap-to-asciidoc)
[![License](https://poser.pugx.org/ubertech-za/tiptap-to-asciidoc/license)](https://packagist.org/packages/ubertech-za/tiptap-to-asciidoc)

## Features

- 🚀 **Direct TipTap JSON parsing** - No HTML intermediary required
- 📝 **Complete AsciiDoc support** - Headers, emphasis, links, images, lists, tables, code blocks
- 🔧 **Extensible architecture** - Add custom converters for specific TipTap nodes
- ⚙️ **Configurable** - Customize conversion behavior with options
- 🧪 **Well tested** - Comprehensive test suite
- 📦 **Framework agnostic** - Works with any PHP project
- 🎯 **Laravel integration** - Optional service provider for Laravel projects

## Installation

Install the package via Composer:

```bash
composer require ubertech-za/tiptap-to-asciidoc
```

**Framework Independence**: This package works standalone with any PHP project. Laravel integration is completely optional and only activated when Laravel is detected in your project.

## Quick Start

```php
use UbertechZa\TipTapToAsciiDoc\TipTapConverter;

$converter = new TipTapConverter();

$tipTapJson = [
    'type' => 'doc',
    'content' => [
        [
            'type' => 'heading',
            'attrs' => ['level' => 1],
            'content' => [
                ['type' => 'text', 'text' => 'Welcome to AsciiDoc']
            ]
        ],
        [
            'type' => 'paragraph',
            'content' => [
                ['type' => 'text', 'text' => 'This is a '],
                ['type' => 'text', 'text' => 'bold', 'marks' => [['type' => 'bold']]],
                ['type' => 'text', 'text' => ' statement with a '],
                ['type' => 'text', 'text' => 'link', 'marks' => [['type' => 'link', 'attrs' => ['href' => 'https://asciidoc.org']]]],
                ['type' => 'text', 'text' => '.']
            ]
        ]
    ]
];

$asciidoc = $converter->convert($tipTapJson);
echo $asciidoc;
```

Output:
```asciidoc
= Welcome to AsciiDoc

This is a *bold* statement with a https://asciidoc.org[link].
```

## Supported TipTap Nodes

### Document Structure
- `doc` - Document root
- `paragraph` - Text paragraphs
- `heading` - Headers (levels 1-6)
- `blockquote` - Quote blocks
- `codeBlock` - Code blocks with language support
- `horizontalRule` - Horizontal rules

### Lists
- `bulletList` - Unordered lists
- `orderedList` - Numbered lists
- `listItem` - List items
- `taskList` - Task/checkbox lists
- `taskItem` - Individual tasks

### Text Formatting
- `text` - Plain text with mark support
- `bold` - Bold formatting
- `italic` - Italic formatting
- `code` - Inline code
- `strike` - Strikethrough text
- `underline` - Underlined text

### Media and Links
- `image` - Images with alt text and attributes
- `link` - Hyperlinks

### Tables
- `table` - Table structures
- `tableRow` - Table rows
- `tableHeader` - Header cells
- `tableCell` - Data cells

### Advanced Features
- `hardBreak` - Line breaks
- `mention` - @mentions and references

## Testing

Run the test suite:

```bash
composer test
```

Run tests with coverage:

```bash
composer test-coverage
```

Run static analysis:

```bash
composer analyse
```

## Contributing

Contributions are welcome! Please see our [contributing guidelines](CONTRIBUTING.md) for details.

## Credits

This package is inspired by and utilizes architectural patterns from [Ueberdosis TipTap](https://github.com/ueberdosis/tiptap) and specifically leverages the [TipTap PHP package](https://github.com/ueberdosis/tiptap-php) for JSON parsing capabilities. We extend our gratitude to the Ueberdosis team for their excellent rich text editor and conversion patterns.

- **Original Architecture**: [Ueberdosis TipTap](https://github.com/ueberdosis/tiptap) (MIT License)
- **PHP JSON Parsing**: [TipTap PHP](https://github.com/ueberdosis/tiptap-php) (MIT License)
- **AsciiDoc Implementation**: [Uber Technologies cc](https://github.com/ubertech-za)
- **Contributors**: [All contributors](../../contributors)

### Architectural Attribution

This package borrows and adapts the following patterns from the TipTap ecosystem:
- JSON document structure and node type definitions
- Node traversal and processing patterns from `ueberdosis/tiptap-php`
- Mark and attribute handling mechanisms
- Extensible converter architecture for different node types

The implementation has been adapted specifically for AsciiDoc output format while maintaining compatibility with the TipTap JSON schema and leveraging the robust parsing capabilities of the official TipTap PHP package.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

## Related Packages

This package is part of the **PHP AsciiDoc Tool Chain** project:

- [ubertech-za/asciidoc-renderer](https://github.com/ubertech-za/asciidoc-renderer) - Blade-based AsciiDoc templating for Laravel
- [ubertech-za/html-to-asciidoc](https://github.com/ubertech-za/html-to-asciidoc) - Convert HTML to AsciiDoc
- [ubertech-za/asciidoctor-wrapper](https://github.com/ubertech-za/asciidoctor-wrapper) - PHP wrapper for Asciidoctor with theming support

Together, these packages enable rich document authoring workflows in familiar web-based editors with professional AsciiDoc output suitable for technical documentation, books, and publishing workflows.

---

**Made by Uber Technologies cc**