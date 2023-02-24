<?php
/**
 * SAM-6827: Enrich AuctionLotItem entity
 * SAM-6822: Enrich entities
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\AuctionLotItem\LotNo;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotNoPureChecker
 * @package Sam\Core\Entity\Model\AuctionLotItem\LotNo
 */
class LotNoPureChecker extends CustomizableClass
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
     * Check lot# is filled.
     * @param int|null $lotNum
     * @return bool
     */
    public function isFilled(?int $lotNum): bool
    {
        return (string)$lotNum !== '';
    }

}
