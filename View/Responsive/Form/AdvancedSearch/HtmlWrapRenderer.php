<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 12, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch;

use Sam\Core\Service\CustomizableClass;

/**
 * Class HtmlWrapRenderer
 */
class HtmlWrapRenderer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $content
     * @param array $attributes
     * @return string
     */
    public function withSpan(string $content, array $attributes = []): string
    {
        $output = $this->withHtmlTag("span", $content, $attributes);
        return $output;
    }

    /**
     * Decorate output string with DIV tag with respective class
     * @param string $content
     * @param array $attributes
     * @return string
     */
    public function withDiv(string $content, array $attributes = []): string
    {
        $output = $this->withHtmlTag("div", $content, $attributes);
        return $output;
    }

    /**
     * @param string $tag
     * @param string $content
     * @param array $attributes
     * @return string
     */
    protected function withHtmlTag(string $tag, string $content, array $attributes = []): string
    {
        $attribute = $this->convertAttributesToString($attributes);
        $output = "<{$tag} {$attribute} >{$content}</{$tag}>";
        return $output;
    }

    /**
     * @param array $attributes
     * @return string
     */
    protected function convertAttributesToString(array $attributes): string
    {
        $attributesEscaped = [];
        foreach ($attributes as $attribute => $value) {
            if (trim($value) !== '') {
                $attributesEscaped[] = sprintf("%s=\"%s\"", ee($attribute), ee($value));
            }
        }
        $output = implode(" ", $attributesEscaped);
        return $output;
    }
}
