<?php
/**
 * SAM-12013: Implementation of admin side translation resource reorganization for v4.0 - Translation Manage Auctions page(list, info).
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 23, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionListForm\Translate\Search;

/**
 * Trait AuctionListSearchTranslatorCreateTrait
 * @package Sam\View\Admin\Form\AuctionListForm\Translate\Search
 */
trait AuctionListSearchTranslatorCreateTrait
{
    protected ?AuctionListSearchTranslator $auctionListSearchTranslator = null;

    /**
     * @return AuctionListSearchTranslator
     */
    protected function createAuctionListSearchTranslator(): AuctionListSearchTranslator
    {
        return $this->auctionListSearchTranslator ?: AuctionListSearchTranslator::new();
    }

    /**
     * @param AuctionListSearchTranslator $auctionListSearchTranslator
     * @return $this
     * @internal
     */
    public function setAuctionListSearchTranslator(AuctionListSearchTranslator $auctionListSearchTranslator): self
    {
        $this->auctionListSearchTranslator = $auctionListSearchTranslator;
        return $this;
    }
}
