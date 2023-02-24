<?php
/**
 * Data load
 *
 * SAM-10431: Refactor rtb catalog renderer for v3-7
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Jul 09, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Catalog\Bidder\Render\Base\Internal\Load;

use LotImage;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoader;
use Sam\Lot\Image\Load\LotImageLoader;
use Sam\Rtb\Catalog\Bidder\Render\Base\Internal\Load\Catalog\DataLoader;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class DataProvider
 */
class DataProvider extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load public catalog data
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadPublicCatalogData(
        int $auctionId,
        bool $isReadOnlyDb = false
    ): array {
        return DataLoader::new()->loadPublicCatalogData($auctionId, $isReadOnlyDb);
    }

    /**
     * Load public catalog data
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadPublicCatalogLotData(
        int $lotItemId,
        int $auctionId,
        bool $isReadOnlyDb = false
    ): array {
        return DataLoader::new()->loadPublicCatalogLotData($lotItemId, $auctionId, $isReadOnlyDb);
    }


    /**
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return LotImage|null
     */
    public function loadLotImage(int $lotItemId, bool $isReadOnlyDb = false): ?LotImage
    {
        return LotImageLoader::new()->loadDefaultForLot($lotItemId, $isReadOnlyDb);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function findPrimaryCurrencySign(bool $isReadOnlyDb = false): string
    {
        return CurrencyLoader::new()->findPrimaryCurrencySign($isReadOnlyDb);
    }
}
