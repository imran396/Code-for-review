<?php
/**
 * SAM-4445: Apply TextFormatter
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Transform\Css;

use Sam\Core\Service\CustomizableClass;

/**
 * @package Sam\Core\Transform\Text
 */
class CssTransformer extends CustomizableClass
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
     * @param string $input
     * @return string
     */
    public function toClassName(string $input): string
    {
        $input = strtolower($input);
        $input = preg_replace("/[^0-9a-z]+/i", "-", $input);
        return trim($input, "-");
    }
}
