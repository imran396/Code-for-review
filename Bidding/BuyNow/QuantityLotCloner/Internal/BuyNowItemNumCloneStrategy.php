<?php
/**
 * This class implements the generation of a new item number for a cloned lot when buying now with selected quantity
 *
 * SAM-3578: Buyer select from quantity for timed buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BuyNow\QuantityLotCloner\Internal;

use LotItem;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Save\ItemNum\ItemNumCloneStrategyInterface;
use Sam\Lot\Validate\LotItemExistenceCheckerAwareTrait;

/**
 * Class BuyNowItemNumCloneStrategy
 * @package Sam\Bidding\BuyNow\QuantityLotCloner
 * @internal
 */
class BuyNowItemNumCloneStrategy extends CustomizableClass implements ItemNumCloneStrategyInterface
{
    use CloneNumberExtensionGeneratorCreateTrait;
    use LotItemExistenceCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Generate a new unique item number for a cloned lot by
     * adding a suffix to the item number extension.
     *
     * @inheritDoc
     */
    public function detectCloneItemNum(int $accountId, int $sourceItemNum, string $sourceItemNumExt): array
    {
        $extensionGenerator = $this->createCloneNumberExtensionGenerator()->construct();
        $index = 0;
        do {
            $extension = $extensionGenerator->generate($sourceItemNumExt, LotItem::ITEM_NUM_EXT_MAX_LENGTH, $index++);
            $isFound = $this->getLotItemExistenceChecker()->existByItemNum($sourceItemNum, $extension, $accountId);
        } while ($isFound);
        return [$sourceItemNum, $extension];
    }
}
