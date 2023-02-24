<?php
/**
 * SAM-9993: Enrich custom field entities
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\LotItemCustField\Status;

use Sam\Core\CustomField\Status\CustomFieldStatusPureChecker;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotItemCustFieldStatusPureChecker
 * @package Sam\Core\Entity\Model\LotItemCustField\Status
 */
class LotItemCustFieldStatusPureChecker extends CustomizableClass
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
        return CustomFieldStatusPureChecker::new()->isNumeric($type);
    }
}
