<?php
/**
 * SAM-1537: Walmart - Bulk Barcode/Image Import
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

namespace Sam\Lot\Image\BucketImport\Associate\Strategy;

use LotImageInBucket;
use LotItem;
use Sam\Lot\Image\BucketImport\Associate\AssociationMap\AssociationMap;

/**
 * Interface BucketImageAssociationStrategyInterface
 * @package Sam\Lot\Image\BucketImport\Associate\Strategy
 */
interface StrategyInterface
{
    /**
     * @param int $auctionId
     * @param LotImageInBucket[] $bucketImages
     * @return AssociationMap
     */
    public function makeAssociationMap(int $auctionId, array $bucketImages): AssociationMap;

    /**
     * Make image filename to save image from bucket.
     * Return existing name of existing file to replace it by image from bucket.
     *
     * @param LotImageInBucket $bucketImage
     * @param LotItem $lotItem
     * @return string
     */
    public function makeLotImageFilename(LotImageInBucket $bucketImage, LotItem $lotItem): string;

    /**
     * @return StrategyValidatorInterface|null
     */
    public function getValidator(): ?StrategyValidatorInterface;
}
