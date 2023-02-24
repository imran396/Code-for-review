<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Associate\Strategy\Filename;

use Sam\Lot\Load\LotItemLoaderAwareTrait;

/**
 * Class AssociateByLotImageFilenameStrategy
 * @package Sam\Lot\Image\BucketImport\Associate\Strategy
 */
class LotImageFilenameStrategy extends FilenameStrategyBase
{
    use LotItemLoaderAwareTrait;

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
        $lotItems = [];
        $lotImages = $this->getLotImageLoader()->loadByAuctionIdAndImageLink($auctionId, $search);
        foreach ($lotImages as $lotImage) {
            $lotItem = $this->getLotItemLoader()->load($lotImage->LotItemId);
            if ($lotItem) {
                $lotItems[] = $lotItem;
            }
        }
        return $lotItems;
    }

    /**
     * @inheritDoc
     */
    protected function splitFilename(string $filename): array
    {
        return [
            'value' => $filename,
            'index' => 0
        ];
    }
}
