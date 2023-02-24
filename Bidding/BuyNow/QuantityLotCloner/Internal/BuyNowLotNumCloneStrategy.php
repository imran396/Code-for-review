<?php
/**
 * This class implements the generation of a new lot number for a cloned auction lot when buying now with selected quantity
 *
 * SAM-3578: Buyer select from quantity for timed buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BuyNow\QuantityLotCloner\Internal;

use AuctionLotItem;
use Sam\AuctionLot\Validate\AuctionLotExistenceCheckerAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BuyNowLotNumCloneStrategy
 * @package Sam\Bidding\BuyNow\QuantityLotCloner
 * @internal
 */
class BuyNowLotNumCloneStrategy extends CustomizableClass
{
    use AuctionLotExistenceCheckerAwareTrait;
    use CloneNumberExtensionGeneratorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Generate a new unique lot number for a cloned auction lot by
     * adding a suffix to the lot number extension.
     *
     * @param int $auctionId
     * @param string $sourceLotNumPrefix
     * @param int $sourceLotNum
     * @param string $sourceLotNumExt
     * @return array
     */
    public function detectCloneLotNum(int $auctionId, string $sourceLotNumPrefix, int $sourceLotNum, string $sourceLotNumExt): array
    {
        $extensionGenerator = $this->createCloneNumberExtensionGenerator()->construct();
        $index = 0;
        do {
            $extension = $extensionGenerator->generate($sourceLotNumExt, AuctionLotItem::LOT_NUM_EXT_MAX_LENGTH, $index++);
            $isFound = $this->getAuctionLotExistenceChecker()->existByLotNo($auctionId, $sourceLotNum, $extension, $sourceLotNumPrefix);
        } while ($isFound);
        return [$sourceLotNumPrefix, $sourceLotNum, $extension];
    }
}
