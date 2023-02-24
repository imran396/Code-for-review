<?php
/**
 * SAM-7958: Refactor \Cached_Queue class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           mar. 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Queue\Load;

/**
 * Trait LotImageQueueLoaderCreateTrait
 * @package Sam\Lot\Image\Queue\Load
 */
trait LotImageQueueLoaderCreateTrait
{
    /**
     * @var LotImageQueueLoader|null
     */
    protected ?LotImageQueueLoader $lotImageQueueLoader = null;

    /**
     * @return LotImageQueueLoader
     */
    protected function createLotImageQueueLoader(): LotImageQueueLoader
    {
        return $this->lotImageQueueLoader ?: LotImageQueueLoader::new();
    }

    /**
     * @param LotImageQueueLoader $lotImageQueueLoader
     * @return static
     * @internal
     */
    public function setLotImageQueueLoader(LotImageQueueLoader $lotImageQueueLoader): static
    {
        $this->lotImageQueueLoader = $lotImageQueueLoader;
        return $this;
    }
}
