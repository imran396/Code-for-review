<?php
/**
 * SAM-4687: Refactor "Unsold Lots" report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\Unsold\Base;

/**
 * Trait DataLoaderAwareTrait
 * @package Sam\Report\Lot\Unsold\Base
 * @method int getFilterAuctionId() - FilterAuctionAwareTrait should be imported in class
 */
trait DataLoaderAwareTrait
{
    protected ?DataLoader $dataLoader = null;

    /**
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new()
                ->filterAuctionId($this->getFilterAuctionId());
        }
        return $this->dataLoader;
    }

    /**
     * @param DataLoader $dataLoader
     * @return static
     * @internal
     */
    public function setDataLoader(DataLoader $dataLoader): static
    {
        $this->dataLoader = $dataLoader;
        return $this;
    }
}
