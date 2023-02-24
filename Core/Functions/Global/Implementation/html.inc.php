<?php
/**
 * SAM-4445: Apply TextFormatter
 */

use Sam\Core\Transform\Text\TextTransformer;

/**
 * Encode html entities
 * Transform text be ready for rendering in html context
 * @param string|null $input $string
 * @param int $quoteStyle
 * @return string
 */
function ee(?string $input, int $quoteStyle = ENT_QUOTES | ENT_SUBSTITUTE): string
{
    return TextTransformer::new()->encodeHtmlEntities((string)$input, $quoteStyle);
}
