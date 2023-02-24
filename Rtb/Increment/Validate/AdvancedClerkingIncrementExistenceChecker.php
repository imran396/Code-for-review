<?php
/**
 * SAM-5346: Rtb asking bid calculator
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/10/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Increment\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\RtbCurrentIncrement\RtbCurrentIncrementReadRepositoryCreateTrait;

/**
 * Class AdvancedClerkingIncrementExistenceChecker
 * @package Sam\Rtb\Increment\Validate
 */
class AdvancedClerkingIncrementExistenceChecker extends CustomizableClass
{
    use RtbCurrentIncrementReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $auctionId
     * @param float|null $increment
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existInAuctionByIncrement(?int $auctionId, ?float $increment, bool $isReadOnlyDb = false): bool
    {
        if (
            !$auctionId
            || !$increment
        ) {
            return false;
        }

        return $this->createRtbCurrentIncrementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterIncrement($increment)
            ->exist();
    }
}
