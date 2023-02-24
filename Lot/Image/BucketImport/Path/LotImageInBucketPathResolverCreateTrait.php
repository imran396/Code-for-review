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

namespace Sam\Lot\Image\BucketImport\Path;

/**
 * Trait LotImageInBucketPathHelperCreateTrait
 * @package Sam\Lot\Image\BucketImport\Path
 */
trait LotImageInBucketPathResolverCreateTrait
{
    /**
     * @var LotImageInBucketPathResolver|null
     */
    protected ?LotImageInBucketPathResolver $lotImageInBucketPathResolver = null;

    /**
     * @return LotImageInBucketPathResolver
     */
    protected function createLotImageInBucketPathResolver(): LotImageInBucketPathResolver
    {
        return $this->lotImageInBucketPathResolver ?: LotImageInBucketPathResolver::new();
    }

    /**
     * @param LotImageInBucketPathResolver $pathResolver
     * @return static
     * @internal
     */
    public function setLotImageInBucketPathResolver(LotImageInBucketPathResolver $pathResolver): static
    {
        $this->lotImageInBucketPathResolver = $pathResolver;
        return $this;
    }
}
