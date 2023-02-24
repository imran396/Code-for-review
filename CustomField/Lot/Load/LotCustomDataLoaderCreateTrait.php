<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 13, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Load;

/**
 * Trait LotCustomDataLoaderCreateTrait
 * @package Sam\CustomField\Lot\Load
 */
trait LotCustomDataLoaderCreateTrait
{
    /**
     * @var LotCustomDataLoader|null
     */
    protected ?LotCustomDataLoader $lotCustomDataLoader = null;

    /**
     * @return LotCustomDataLoader
     */
    protected function createLotCustomDataLoader(): LotCustomDataLoader
    {
        return $this->lotCustomDataLoader ?: LotCustomDataLoader::new();
    }

    /**
     * @param LotCustomDataLoader $lotCustomDataLoader
     * @return static
     * @internal
     */
    public function setLotCustomDataLoader(LotCustomDataLoader $lotCustomDataLoader): static
    {
        $this->lotCustomDataLoader = $lotCustomDataLoader;
        return $this;
    }
}
