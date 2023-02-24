<?php
/**
 * SAM-4629: Refactor custom lot report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\CustomList\Load;

/**
 * Trait LotCustomListDataLoaderAwareTrait
 * @package Sam\Report\Lot\CustomList
 */
trait LotCustomListDataLoaderAwareTrait
{
    protected ?LotCustomListDataLoader $lotCustomListDataLoader = null;

    /**
     * @return LotCustomListDataLoader
     */
    protected function getLotCustomListDataLoader(): LotCustomListDataLoader
    {
        if ($this->lotCustomListDataLoader === null) {
            $this->lotCustomListDataLoader = LotCustomListDataLoader::new();
        }
        return $this->lotCustomListDataLoader;
    }

    /**
     * @param LotCustomListDataLoader $lotCustomListDataLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotCustomListDataLoader(LotCustomListDataLoader $lotCustomListDataLoader): static
    {
        $this->lotCustomListDataLoader = $lotCustomListDataLoader;
        return $this;
    }
}
