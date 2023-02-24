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

namespace Sam\Lot\Image\BucketImport\Save;

/**
 * Trait BucketImportLotImageProducerCreateTrait
 * @package Sam\Lot\Image\BucketImport\Save
 */
trait BucketImportLotImageProducerCreateTrait
{
    /**
     * @var BucketImportLotImageProducer|null
     */
    protected ?BucketImportLotImageProducer $bucketImportLotImageProducer = null;

    /**
     * @return BucketImportLotImageProducer
     */
    protected function createBucketImportLotImageProducer(): BucketImportLotImageProducer
    {
        return $this->bucketImportLotImageProducer ?: BucketImportLotImageProducer::new();
    }

    /**
     * @param BucketImportLotImageProducer $bucketImportLotImageProducer
     * @return static
     * @internal
     */
    public function setBucketImportLotImageProducer(BucketImportLotImageProducer $bucketImportLotImageProducer): static
    {
        $this->bucketImportLotImageProducer = $bucketImportLotImageProducer;
        return $this;
    }
}
