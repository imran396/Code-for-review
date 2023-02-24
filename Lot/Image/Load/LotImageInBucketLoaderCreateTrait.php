<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Load;

/**
 * Trait LotImageInBucketLoaderCreateTrait
 * @package Sam\Lot\Image\Load
 */
trait LotImageInBucketLoaderCreateTrait
{
    /**
     * @var LotImageInBucketLoader|null
     */
    protected ?LotImageInBucketLoader $lotImageInBucketLoader = null;

    /**
     * @return LotImageInBucketLoader
     */
    protected function createLotImageInBucketLoader(): LotImageInBucketLoader
    {
        return $this->lotImageInBucketLoader ?: LotImageInBucketLoader::new();
    }

    /**
     * @param LotImageInBucketLoader $lotImageInBucketLoader
     * @return static
     * @internal
     */
    public function setLotImageInBucketLoader(LotImageInBucketLoader $lotImageInBucketLoader): static
    {
        $this->lotImageInBucketLoader = $lotImageInBucketLoader;
        return $this;
    }
}
