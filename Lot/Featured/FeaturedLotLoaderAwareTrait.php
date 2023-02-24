<?php
/**
 * SAM-5152: Featured lot loader
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           02.06.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Lot\Featured;

/**
 * Trait FeaturedLotLoaderAwareTrait
 * @package Sam\Lot\Featured
 */
trait FeaturedLotLoaderAwareTrait
{
    /**
     * @var FeaturedLotLoader|null
     */
    protected ?FeaturedLotLoader $featuredLotLoader = null;

    /**
     * @return FeaturedLotLoader
     */
    protected function getFeaturedLotLoader(): FeaturedLotLoader
    {
        if ($this->featuredLotLoader === null) {
            $this->featuredLotLoader = FeaturedLotLoader::new();
        }
        return $this->featuredLotLoader;
    }

    /**
     * @param FeaturedLotLoader $featuredLotLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setFeaturedLotLoader(FeaturedLotLoader $featuredLotLoader): static
    {
        $this->featuredLotLoader = $featuredLotLoader;
        return $this;
    }
}
