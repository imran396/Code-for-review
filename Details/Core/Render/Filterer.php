<?php
/**
 * Apply filters
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Feb 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Core\Render;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Details\Core\Placeholder\Placeholder;

/**
 * Class Filterer
 * @package Sam\Details
 */
class Filterer extends CustomizableClass
{
    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Apply filters to value
     */
    public function apply(string $value, Placeholder $placeholder): string
    {
        $functions = $placeholder->getOptionValue("flt");
        if ($functions) {
            foreach ($functions as $props) {
                $function = $props['function'];
                $arguments = $props['arguments'];
                if ($function === 'Length') {
                    $length = isset($arguments[0]) && NumberValidator::new()->isIntPositive($arguments[0]) ? $arguments[0] : null;
                    $value = $this->cutLength($value, $length);
                } elseif ($function === 'StripTags') {
                    $value = (new \Laminas\Filter\StripTags())->filter($value);
                } elseif ($function === 'StringTrim') {
                    $charList = isset($arguments[0]) && is_string($arguments[0]) ? $arguments[0] : null;
                    $value = (new \Laminas\Filter\StringTrim($charList))->filter($value);
                } elseif ($function === 'StripNewlines') {
                    $value = (string)(new \Laminas\Filter\StripNewlines())->filter($value);
                }
            }
        }
        return $value;
    }

    /**
     * Cut length if respective placeholder option is set
     */
    protected function cutLength(string $value, ?int $length): string
    {
        if ($length === null) {
            return $value;
        }
        return TextTransformer::new()->cut($value, $length);
    }
}
