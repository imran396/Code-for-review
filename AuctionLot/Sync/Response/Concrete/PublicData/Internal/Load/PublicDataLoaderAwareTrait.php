<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal\Load;


/**
 * Trait PublicDataLoaderAwareTrait
 * @package Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal\Load
 * @internal
 */
trait PublicDataLoaderAwareTrait
{
    protected ?PublicDataLoader $publicDataLoader = null;

    /**
     * @return PublicDataLoader
     */
    protected function getPublicDataLoader(): PublicDataLoader
    {
        if ($this->publicDataLoader === null) {
            $this->publicDataLoader = PublicDataLoader::new();
        }
        return $this->publicDataLoader;
    }

    /**
     * @param PublicDataLoader $publicDataLoader
     * @return static
     * @internal
     */
    public function setPublicDataLoader(PublicDataLoader $publicDataLoader): static
    {
        $this->publicDataLoader = $publicDataLoader;
        return $this;
    }
}
