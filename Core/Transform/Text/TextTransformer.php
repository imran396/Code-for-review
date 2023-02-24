<?php
/**
 * General text rendering methods.
 *
 * SAM-4445: Apply TextFormatter
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Transform\Text;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TextRenderer
 * @package Sam\Core\Transform\Text
 */
class TextTransformer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Limit input length and append suffix string instead exceeded ending characters.
     * @param string $input
     * @param int $limit
     * @param string $suffix
     * @return string
     * #[Pure]
     */
    public function cut(string $input, int $limit, string $suffix = ''): string
    {
        if (mb_strlen($input) <= $limit) {
            return $input;
        }
        $output = mb_substr($input, 0, $limit, 'UTF-8') . $suffix;
        return $output;
    }

    /**
     * Check if it is valid UTF-8 and make htmlentities()
     *
     * If a string is not a valid UTF-8 string, it will try to cut off one character at the end
     * and try to validate the result since some database record end on half a multibyte character.
     *
     * @param string $input
     * @param int $quoteStyle
     * @return string
     * #[Pure]
     */
    public function encodeHtmlEntities(string $input, int $quoteStyle = ENT_QUOTES | ENT_SUBSTITUTE): string
    {
        $input = $this->fixUnicodeCrop($input);
        return htmlentities($input, $quoteStyle, 'UTF-8');
    }

    public function fixUnicodeCrop(string $input): string
    {
        if (mb_check_encoding($input, 'UTF-8') === false) {
            $input = substr($input, 0, -1);
        }
        if (mb_check_encoding($input, 'UTF-8') === false) {
            return Constants\TextTransform::CHARACTER_ENCODING_ERROR_MARKER;
        }
        return $input;
    }

    /**
     * Strip all non displayable chars.
     *
     * @param string $text
     * @return string
     * #[Pure]
     */
    public function filterNonDisplayableChars(string $text): string
    {
        return (string)preg_replace('/[\x0-\x8\xb\xc\xe-\x1f]/mu', '', $text);
    }

    /**
     * Replace whitespaces with replacement string
     * @param string $input
     * @param string $replacement
     * @return string|null
     * #[Pure]
     */
    public function replaceWhitespaces(string $input, string $replacement): ?string
    {
        return preg_replace('/\s+/u', $replacement, $input);
    }

    /**
     * Simple function that strip all space
     * @param string $input
     * @return string|null
     * #[Pure]
     */
    public function stripWhitespaces(string $input): ?string
    {
        return $this->replaceWhitespaces($input, '');
    }

    /**
     * Convert input to CamelCased view from words delimited by any non-alphanumeric character
     * @param string $input
     * @return string
     * #[Pure]
     */
    public function toCamelCase(string $input): string
    {
        $output = $input; // copy to allow easy changing replacement order below if necessary
        $output = preg_replace("/[^A-Za-z0-9]/", " ", $output);
        $output = ucwords($output);
        $output = str_replace(" ", "", $output);
        return $output;
    }

    /**
     * Convert input to pascalCased view from words delimited by any non-alphanumeric character
     * @param string $input
     * @return string
     * #[Pure]
     */
    public function toPascalCase(string $input): string
    {
        $output = $input;
        $output = preg_replace("/[^A-Za-z0-9]/", " ", $output);
        $output = ucwords($output);
        $output = str_replace(" ", "", $output);
        $output = lcfirst($output);
        return $output;
    }

    /**
     * Convert text to parameter for seo friendly url (SAM-2869)
     * @param string $text
     * @return string
     * #[Pure]
     */
    public function toSeoFriendlyUrl(string $text): string
    {
        $text = preg_replace('/[^a-zA-Z0-9]+/u', '-', $text);
        $text = trim($text, '-');
        return $text;
    }
}
