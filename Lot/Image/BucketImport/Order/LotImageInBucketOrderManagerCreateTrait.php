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
 * Trait LotImageInBucketOrderManagerCreateTrait
 * @package Sam\Lot\Image\BucketImport\Order
 */
trait LotImageInBucketOrderManagerCreateTrait
{
    /**
     * @var LotImageInBucketOrderManager|null
     */
    protected ?LotImageInBucketOrderManager $lotImageInBucketOrderManager = null;

    /**
     * @return LotImageInBucketOrderManager
     */
    protected function createLotImageInBucketOrderManager(): LotImageInBucketOrderManager
    {
        return $this->lotImageInBucketOrderManager ?: LotImageInBucketOrderManager::new();
    }

    /**
     * @param LotImageInBucketOrderManager $lotImageInBucketOrderManager
     * @return static
     * @internal
     */
    public function setLotImageInBucketOrderManager(LotImageInBucketOrderManager $lotImageInBucketOrderManager): static
    {
        $this->lotImageInBucketOrderManager = $lotImageInBucketOrderManager;
        return $this;
    }
}
