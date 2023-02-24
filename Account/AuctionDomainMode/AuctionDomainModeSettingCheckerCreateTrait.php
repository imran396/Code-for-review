<?php
/**
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 12, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\AuctionDomainMode;

/**
 * Trait AuctionDomainModeSettingCheckerCreateTrait
 * @package Sam\Account\AuctionDomainMode
 */
trait AuctionDomainModeSettingCheckerCreateTrait
{
    protected ?AuctionDomainModeSettingChecker $auctionDomainModeSettingChecker = null;

    /**
     * @return AuctionDomainModeSettingChecker
     */
    protected function createAuctionDomainModeSettingChecker(): AuctionDomainModeSettingChecker
    {
        return $this->auctionDomainModeSettingChecker ?: AuctionDomainModeSettingChecker::new();
    }

    /**
     * @param AuctionDomainModeSettingChecker $auctionDomainModeSettingChecker
     * @return static
     * @internal
     */
    public function setAuctionDomainModeSettingChecker(AuctionDomainModeSettingChecker $auctionDomainModeSettingChecker): static
    {
        $this->auctionDomainModeSettingChecker = $auctionDomainModeSettingChecker;
        return $this;
    }
}
