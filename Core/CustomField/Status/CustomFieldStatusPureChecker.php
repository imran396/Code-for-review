<?php
/**
 * SAM-9993: Enrich custom field entities
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\CustomField\Status;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class CustomFieldStatusPureChecker
 * @package Sam\Core\CustomField\Status
 */
class CustomFieldStatusPureChecker extends CustomizableClass
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
     * Check if custom field of numeric type
     * @param int|null $type
     * @return bool
     */
    public function isNumeric(?int $type): bool
    {
        return in_array($type, Constants\CustomField::$numericTypes, true);
    }
}
