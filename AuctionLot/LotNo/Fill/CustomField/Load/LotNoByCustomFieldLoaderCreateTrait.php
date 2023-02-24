<?php
/**
 * SAM-5651: Refactor Lot No auto filling service
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\LotNo\Fill\CustomField\Load;

/**
 * Trait lotNoByCustomFieldLoaderCreateTrait
 * @package SSam\AuctionLot\LotNo\Fill\CustomField\Load
 */
trait LotNoByCustomFieldLoaderCreateTrait
{
    /**
     * @var LotNoByCustomFieldLoader|null
     */
    protected ?LotNoByCustomFieldLoader $lotNoByCustomFieldLoader = null;

    /**
     * @return LotNoByCustomFieldLoader
     */
    protected function createLotNoByCustomFieldLoader(): LotNoByCustomFieldLoader
    {
        return $this->lotNoByCustomFieldLoader ?: LotNoByCustomFieldLoader::new();
    }

    /**
     * @param LotNoByCustomFieldLoader $lotNoByCustomFieldLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotNoByCustomFieldLoader(LotNoByCustomFieldLoader $lotNoByCustomFieldLoader): static
    {
        $this->lotNoByCustomFieldLoader = $lotNoByCustomFieldLoader;
        return $this;
    }
}
