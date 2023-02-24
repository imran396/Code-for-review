<?php
/**
 * Lot Item Offer Bid Data Loader Create Trait
 *
 * SAM-6248: Refactor Lot Info Panel at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\OfferBid\Load;

/**
 * Trait LotItemOfferBidDataLoaderCreateTrait
 */
trait LotItemOfferBidDataLoaderCreateTrait
{
    protected ?LotItemOfferBidDataLoader $lotItemOfferBidDataLoader = null;

    /**
     * @return LotItemOfferBidDataLoader
     */
    protected function createLotItemOfferBidDataLoader(): LotItemOfferBidDataLoader
    {
        return $this->lotItemOfferBidDataLoader ?: LotItemOfferBidDataLoader::new();
    }

    /**
     * @param LotItemOfferBidDataLoader $lotItemOfferBidDataLoader
     * @return static
     * @internal
     */
    public function setLotItemOfferBidDataLoader(LotItemOfferBidDataLoader $lotItemOfferBidDataLoader): static
    {
        $this->lotItemOfferBidDataLoader = $lotItemOfferBidDataLoader;
        return $this;
    }
}
