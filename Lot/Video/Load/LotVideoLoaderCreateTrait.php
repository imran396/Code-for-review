<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Boanerge Regidor
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/30/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Video\Load;

/**
 * Trait LotVideoLoaderAwareTrait
 * @package Sam\Lot\Video\Load
 */
trait LotVideoLoaderCreateTrait
{
    /**
     * @var LotVideoLoader|null
     */
    protected ?LotVideoLoader $lotVideoLoader = null;

    /**
     * @return LotVideoLoader
     */
    protected function createLotVideoLoader(): LotVideoLoader
    {
        return $this->lotVideoLoader ?: LotVideoLoader::new();
    }

    /**
     * @param LotVideoLoader $lotVideoLoader
     * @return static
     * @internal
     */
    public function setLotVideoLoader(LotVideoLoader $lotVideoLoader): static
    {
        $this->lotVideoLoader = $lotVideoLoader;
        return $this;
    }
}
