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

/**
 * Interface StrategyValidatorInterface
 * @package Sam\Lot\Image\BucketImport\Associate\Strategy
 */
interface StrategyValidatorInterface
{
    /**
     * @param LotImageInBucket[] $bucketImages
     * @return bool
     */
    public function validate(array $bucketImages): bool;

    /**
     * @return string[]
     */
    public function errorMessages(): array;
}
