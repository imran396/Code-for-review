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

namespace Sam\Lot\Image\BucketImport\Delete;

/**
 * Trait LotImageInBucketDeleterCreateTrait
 * @package Sam\Lot\Image\BucketImport\Delete
 */
trait LotImageInBucketDeleterCreateTrait
{
    /**
     * @var LotImageInBucketDeleter|null
     */
    protected ?LotImageInBucketDeleter $lotImageInBucketDeleter = null;

    /**
     * @return LotImageInBucketDeleter
     */
    protected function createLotImageInBucketDeleter(): LotImageInBucketDeleter
    {
        return $this->lotImageInBucketDeleter ?: LotImageInBucketDeleter::new();
    }

    /**
     * @param LotImageInBucketDeleter $lotImageInBucketDeleter
     * @return static
     * @internal
     */
    public function setLotImageInBucketDeleter(LotImageInBucketDeleter $lotImageInBucketDeleter): static
    {
        $this->lotImageInBucketDeleter = $lotImageInBucketDeleter;
        return $this;
    }
}
