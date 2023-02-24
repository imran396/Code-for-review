<?php
/**
 * General html rendering functions.
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

namespace Sam\Core\Transform\Html;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Text\TextTransformer;

/**
 * Class HtmlRenderer
 * @package Sam\Core\Transform\Text
 */
class HtmlRenderer extends CustomizableClass
{
    public const CUT_WITH_CLARIFICATION_TPL = '<span title="%s">%s...</span>';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Replace all <br\> html tags with "\n" (new line symbol)
     * @param string $string
     * @return string
     */
    public function br2nl(string $string): string
    {
        $newLineChar = chr(13) . chr(10);
        return preg_replace('/<br\s*\/?\s*>/i', $newLineChar, $string);
    }

    /**
     * Cut input length according limit and locate it in template, where the second placeholder is replaced by full input.
     * @param string $input
     * @param int $limit
     * @param string $template
     * @return string
     */
    public function cutWithClarification(
        string $input,
        int $limit,
        string $template = self::CUT_WITH_CLARIFICATION_TPL
    ): string {
        if (mb_strlen($input) <= $limit) {
            return ee($input);
        }
        return sprintf(
            $template,
            ee($input),
            ee(TextTransformer::new()->cut($input, $limit))
        );
    }

    /**
     * Decode HTML entity.
     * See list of all HTML entities: https://www.freeformatter.com/html-entities.html
     *
     * @param string $string
     * @return string
     */
    public function decodeHtmlEntity(string $string): string
    {
        return html_entity_decode($string, ENT_QUOTES | ENT_XML1 | ENT_HTML401 | ENT_XHTML | ENT_HTML5, 'UTF-8');
    }

    /**
     * Convert all windows/linux line breaks into HTML line breaks
     * @param string $line string
     * @return string
     */
    public function newlinesToHtmlBreaks(string $line): string
    {
        $pattern = mb_check_encoding($line, 'UTF-8') ? '/\R/u' : '/\R/';
        return preg_replace($pattern, '<br/>', $line);
    }

    /**
     * Construct <img> html tag with attributes
     * @param string $tag - full url or relative path
     * @param array $attributes
     * @return string
     */
    public function makeImgHtmlTag(string $tag, array $attributes = []): string
    {
        // no 'img' tag check
        if ($tag !== '' && $tag !== 'img') {
            return '';
        }

        $output = '';
        if (
            $tag === 'img'
            && empty($attributes['src'])
        ) {
            // <img /> tag should contain "src" attribute
            return $output;
        }

        $props = [];
        ksort($attributes);
        foreach ($attributes as $key => $value) {
            $props[] = "{$key}=\"{$value}\"";
        }
        $attributeList = implode(' ', $props);
        $output = "<img {$attributeList} />";
        return $output;
    }

    /**
     * Strip HTML tags, replace <br/> with new line char and decode HTML entities related to quotes.
     * See list of all HTML entities: https://www.freeformatter.com/html-entities.html
     *
     * @param string $string
     * @param string $charset
     * @return string
     */
    public function replaceTags(string $string, string $charset = 'UTF-8'): string
    {
        return strip_tags(html_entity_decode($this->br2nl($string), ENT_QUOTES, $charset));
    }
}
