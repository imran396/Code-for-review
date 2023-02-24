<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 2, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Save;

/**
 * Trait LotImageInBucketProducerCreateTrait
 * @package Sam\Lot\Image\BucketImport\Save
 */
trait LotImageInBucketProducerCreateTrait
{
    /**
     * @var LotImageInBucketProducer|null
     */
    protected ?LotImageInBucketProducer $lotImageInBucketProducer = null;

    /**
     * @return LotImageInBucketProducer
     */
    protected function createLotImageInBucketProducer(): LotImageInBucketProducer
    {
        return $this->lotImageInBucketProducer ?: LotImageInBucketProducer::new();
    }

    /**
     * @param LotImageInBucketProducer $lotImageInBucketProducer
     * @return static
     * @internal
     */
    public function setLotImageInBucketProducer(LotImageInBucketProducer $lotImageInBucketProducer): static
    {
        $this->lotImageInBucketProducer = $lotImageInBucketProducer;
        return $this;
    }
}
