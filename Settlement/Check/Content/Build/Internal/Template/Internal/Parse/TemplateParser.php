<?php
/**
 * Template parsing operations, like:
 * - detecting actual placeholders that can be found in template;
 * - replacing placeholders in template with fetched values from DB.
 *
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Build\Internal\Template\Internal\Parse;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Content\Common\Render\GeneralRenderer;

/**
 * Class TemplateParser
 * @package Sam\Settlement\Check
 */
class TemplateParser extends CustomizableClass
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
     * Extract array of actual placeholders that are found in template.
     * Don't extract unknown placeholders.
     * @param string $template
     * @param array $allPlaceholders
     * @return array
     * #[Pure]
     */
    public function extractPlaceholders(string $template, array $allPlaceholders): array
    {
        $results = [];
        $placeholderRenderer = GeneralRenderer::new();
        foreach ($allPlaceholders as $placeholder) {
            $placeholderView = $placeholderRenderer->makePlaceholderView($placeholder);
            if (str_contains($template, $placeholderView)) {
                $results[] = $placeholder;
            }
        }
        $results = array_unique($results);
        return $results;
    }

    /**
     * Replace placeholders in template with values.
     * @param string $template
     * @param array $placeholdersToValues
     * @return string
     * #[Pure]
     */
    public function replacePlaceholders(string $template, array $placeholdersToValues): string
    {
        $output = $template;
        $placeholderRenderer = GeneralRenderer::new();
        foreach ($placeholdersToValues as $placeholder => $value) {
            $placeholderView = $placeholderRenderer->makePlaceholderView($placeholder);
            $output = str_replace($placeholderView, $value, $output);
        }
        return $output;
    }
}
