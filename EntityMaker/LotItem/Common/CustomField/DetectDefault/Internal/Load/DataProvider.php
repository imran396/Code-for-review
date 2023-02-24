<?php
/**
 * SAM-10648: Improve unit test of Lot entity-maker - LotCustomFieldDefaultDetector
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Common\CustomField\DetectDefault\Internal\Load;

use Auction;
use LotCategoryCustData;
use Sam\Auction\Load\AuctionLoader;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\TextType\Barcode\Build\BarcodeGenerator;
use Sam\Lot\Category\CustomField\LotCategoryCustomDataLoader;
use Sam\Lot\Category\Load\LotCategoryLoader;

/**
 * Class DataProvider
 * @package Sam\
 */
class DataProvider extends CustomizableClass
{
    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    public function loadCategoryCustomDataForAutoComplete(
        ?int $lotCategoryId,
        int $lotCustomFieldId,
        bool $isReadOnlyDb = false
    ): ?LotCategoryCustData {
        return $lotCategoryId
            ? LotCategoryCustomDataLoader::new()->load($lotCategoryId, $lotCustomFieldId, $isReadOnlyDb)
            : null;
    }

    public function loadAuction(?int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return AuctionLoader::new()->load($auctionId, $isReadOnlyDb);
    }

    public function generateBarcode(int $lotCustomFieldId, bool $isReadOnlyDb = false): string
    {
        return BarcodeGenerator::new()->generateBarcode($lotCustomFieldId, $isReadOnlyDb);
    }

    public function loadLotCategoryIdByName(string $name, bool $isReadOnlyDb = false): ?int
    {
        return LotCategoryLoader::new()->loadByName($name, $isReadOnlyDb)?->Id ?? null;
    }
}
