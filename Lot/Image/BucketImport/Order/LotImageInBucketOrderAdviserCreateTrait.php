<?php
/**
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

namespace Sam\Lot\Image\BucketImport\Order;

/**
 * Trait LotImageInBucketOrderAdviserCreateTrait
 * @package Sam\Lot\Image\BucketImport\Order
 */
trait LotImageInBucketOrderAdviserCreateTrait
{
    protected ?LotImageInBucketOrderAdviser $lotImageInBucketOrderAdviser = null;

    /**
     * @return LotImageInBucketOrderAdviser
     */
    protected function createLotImageInBucketOrderAdviser(): LotImageInBucketOrderAdviser
    {
        return $this->lotImageInBucketOrderAdviser ?: LotImageInBucketOrderAdviser::new();
    }

    /**
     * @param LotImageInBucketOrderAdviser $lotImageInBucketOrderAdviser
     * @return static
     * @internal
     */
    public function setLotImageInBucketOrderAdviser(LotImageInBucketOrderAdviser $lotImageInBucketOrderAdviser): static
    {
        $this->lotImageInBucketOrderAdviser = $lotImageInBucketOrderAdviser;
        return $this;
    }
}
