<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Associate\Strategy\Filename;

use Sam\Lot\Image\BucketImport\Associate\Strategy\Filename\Internal\Load\FilenamePatternAssociationLotLoaderCreateTrait;

/**
 * Class AssociateByItemNumberStrategy
 * @package Sam\Lot\Image\BucketImport\Associate\Strategy
 */
class ItemNumberStrategy extends FilenameStrategyBase
{
    use FilenamePatternAssociationLotLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    protected function findLotItems(string $search, int $auctionId): array
    {
        return $this->createFilenamePatternAssociationLotLoader()->loadByItemNumber((int)$search, $auctionId);
    }
}
