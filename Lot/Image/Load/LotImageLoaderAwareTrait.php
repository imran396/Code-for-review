<?php
/**
 * SAM-4464: Apply Lot Image Loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/19/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Load;

/**
 * Trait LotImageLoaderAwareTrait
 * @package Sam\Lot\Image\Load
 */
trait LotImageLoaderAwareTrait
{
    /**
     * @var LotImageLoader|null
     */
    protected ?LotImageLoader $lotImageLoader = null;

    /**
     * @return LotImageLoader
     */
    protected function getLotImageLoader(): LotImageLoader
    {
        if ($this->lotImageLoader === null) {
            $this->lotImageLoader = LotImageLoader::new();
        }
        return $this->lotImageLoader;
    }

    /**
     * @param LotImageLoader $lotImageLoader
     * @return static
     * @internal
     */
    public function setLotImageLoader(LotImageLoader $lotImageLoader): static
    {
        $this->lotImageLoader = $lotImageLoader;
        return $this;
    }
}
