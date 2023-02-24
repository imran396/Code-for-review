<?php
/**
 * Trait can be used to prevent repeated and redundant domain service instance creation
 *
 * SAM-6827: Enrich AuctionLotItem entity
 * SAM-6822: Enrich entities
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\AuctionLotItem\Status;

/**
 * Trait AuctionLotStatusPureCheckerAwareTrait
 * @package Sam\Core\Entity\Model\AuctionLotItem\Status
 */
trait AuctionLotStatusPureCheckerAwareTrait
{
    /**
     * @var AuctionLotStatusPureChecker|null
     */
    protected ?AuctionLotStatusPureChecker $auctionLotStatusPureChecker = null;

    /**
     * @return AuctionLotStatusPureChecker
     */
    protected function getAuctionLotStatusPureChecker(): AuctionLotStatusPureChecker
    {
        if ($this->auctionLotStatusPureChecker === null) {
            $this->auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        }
        return $this->auctionLotStatusPureChecker;
    }

    /**
     * @param AuctionLotStatusPureChecker $auctionLotStatusPureChecker
     * @return $this
     * @internal
     */
    public function setAuctionLotStatusPureChecker(AuctionLotStatusPureChecker $auctionLotStatusPureChecker): static
    {
        $this->auctionLotStatusPureChecker = $auctionLotStatusPureChecker;
        return $this;
    }
}
