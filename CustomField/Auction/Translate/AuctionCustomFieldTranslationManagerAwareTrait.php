<?php
/**
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 3, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Translate;

/**
 * Trait AuctionCustomFieldTranslationManagerAwareTrait
 * @package Sam\CustomField\Auction\Translate
 */
trait AuctionCustomFieldTranslationManagerAwareTrait
{
    protected ?AuctionCustomFieldTranslationManager $auctionCustomFieldTranslationManager = null;

    /**
     * @return AuctionCustomFieldTranslationManager
     */
    protected function getAuctionCustomFieldTranslationManager(): AuctionCustomFieldTranslationManager
    {
        if ($this->auctionCustomFieldTranslationManager === null) {
            $this->auctionCustomFieldTranslationManager = AuctionCustomFieldTranslationManager::new();
        }
        return $this->auctionCustomFieldTranslationManager;
    }

    /**
     * @param AuctionCustomFieldTranslationManager $auctionCustomFieldTranslationManager
     * @return static
     * @internal
     */
    public function setAuctionCustomFieldTranslationManager(AuctionCustomFieldTranslationManager $auctionCustomFieldTranslationManager): static
    {
        $this->auctionCustomFieldTranslationManager = $auctionCustomFieldTranslationManager;
        return $this;
    }
}
