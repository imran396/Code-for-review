<?php
/**
 * SAM-6867: Enrich LotItem entity
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\LotItem\Status;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotItemStatusPureChecker
 * @package Sam\Core\Entity\Model\LotItem\Status
 */
class LotItemStatusPureChecker extends CustomizableClass
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
     * Check, if lot item status is active.
     * @param bool|null $active null check JIC
     * @return bool
     */
    public function isActive(?bool $active): bool
    {
        return $active === true;
    }

    /**
     * Check, if lot item status is soft-deleted.
     * @param bool|null $active null check JIC
     * @return bool
     */
    public function isDeleted(?bool $active): bool
    {
        return $active === false;
    }
}
