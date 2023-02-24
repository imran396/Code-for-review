<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Save\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Image\BucketImport\Save\Internal\Load\Dto\LotItemDto;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\Lot\Image\BucketImport\Save\Internal\Load
 * @internal
 */
class DataLoader extends CustomizableClass
{
    use AuctionReadRepositoryCreateTrait;
    use LotItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return LotItemDto|null
     */
    public function loadLotItemDto(int $lotItemId, bool $isReadOnlyDb = false): ?LotItemDto
    {
        $row = $this->createLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($lotItemId)
            ->select(['id', 'account_id', 'name'])
            ->loadRow();
        $dto = $row ? LotItemDto::new()->fromDbRow($row) : null;
        return $dto;
    }

    /**
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isTestAuction(?int $auctionId, bool $isReadOnlyDb = false): bool
    {
        if (!$auctionId) {
            return false;
        }
        $result = $this->createAuctionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($auctionId)
            ->select(['test_auction'])
            ->loadRow();
        return $result && $result['test_auction'];
    }
}
