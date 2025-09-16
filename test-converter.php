<?php

require_once 'vendor/autoload.php';

use UbertechZa\TipTapToAsciiDoc\TipTapToAsciiDocConverter;

// Load the sample Tiptap JSON
$jsonContent = file_get_contents(__DIR__ . '/samples/tiptap-sample.json');

// Create converter and convert
$converter = new TipTapToAsciiDocConverter();
$result = $converter->convert($jsonContent);

echo "=== TIPTAP TO ASCIIDOC CONVERSION ===\n\n";
echo $result;
echo "\n=== END CONVERSION ===\n";