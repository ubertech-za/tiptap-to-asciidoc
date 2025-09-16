<?php

namespace UbertechZa\TipTapToAsciiDoc\Contracts;

interface Converter
{
    /** @param mixed $input */
    public function convert($input): string;
}
