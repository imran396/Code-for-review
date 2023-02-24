<?php
/**
 * SAM-6827: Enrich AuctionLotItem entity
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\AuctionLotItem\BuyNow;

use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BuyNowPureChecker
 * @package Sam\Core\Entity\Model\AuctionLotItem\BuyNow
 */
class BuyNowPureChecker extends CustomizableClass
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
     * Check, if "Buy Now Amount" is set.
     * @param float|null $buyNowAmount
     * @return bool
     */
    public function isBuyNowAmount(?float $buyNowAmount): bool
    {
        return Floating::gt($buyNowAmount, 0.);
    }

}
