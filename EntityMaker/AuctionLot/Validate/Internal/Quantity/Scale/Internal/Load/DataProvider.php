<?php
/**
 * SAM-8005: Allow decimals in quantity
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Scale\Internal\Load;

use InvalidArgumentException;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Core\Constants;

/**
 * Class DataProvider
 * @package Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Scale\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use AuctionLotLoaderAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function detectQuantityScaleForAuctionLot(int $auctionLotId, bool $isReadOnlyDb = false): int
    {
        $auctionLot = $this->getAuctionLotLoader()->loadById($auctionLotId, $isReadOnlyDb);
        if (!$auctionLot) {
            throw new InvalidArgumentException("Cannot load AuctionLot" . composeSuffix(['ali' => $auctionLotId]));
        }
        return $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($auctionLot->LotItemId, $auctionLot->AuctionId, $isReadOnlyDb);
    }

    public function detectQuantityScaleForLotItem(int $lotItemId, bool $isReadOnlyDb = false): int
    {
        return $this->createLotQuantityScaleLoader()->loadLotItemQuantityScale($lotItemId, $isReadOnlyDb);
    }

    public function detectQuantityScaleForAccount(int $accountId): int
    {
        return (int)$this->getSettingsManager()->get(Constants\Setting::QUANTITY_DIGITS, $accountId);
    }

    public function detectQuantityScaleForCategory(string $categoryName): ?int
    {
        return $this->getLotCategoryLoader()->loadByName(trim($categoryName))->QuantityDigits ?? null;
    }
}
