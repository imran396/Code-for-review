<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder;

use Sam\Core\Service\CustomizableClass;

/**
 * Class SmsTemplatePlaceholderDetector
 * @package Sam\Sms\Template\Placeholder\Internal
 */
class SmsTemplatePlaceholderDetector extends CustomizableClass
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
     * @param string $string
     * @return SmsTemplatePlaceholder[]
     */
    public function detectAllPlaceholders(string $string): array
    {
        if (preg_match_all('/{([\w\-]+)(\[([^\s{}\[]+)\])*}/', $string, $matches)) {
            $placeholders = [];
            foreach ($matches[0] as $index => $placeholder) {
                $key = $matches[1][$index];
                $clarification = $matches[2][$index] ? $matches[3][$index] : null;
                //We use an array with a placeholder value as a key to avoid duplication
                $placeholders[$placeholder] = SmsTemplatePlaceholder::new()->construct($placeholder, $key, $clarification);
            }
            return array_values($placeholders);
        }
        return [];
    }
}
