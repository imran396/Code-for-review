<?php
/**
 * SAM-8940: Fix access check of lot editing in soap
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Common\Access;

/**
 * Trait AuctionLotMakerAccessCheckerAwareTrait
 * @package Sam\EntityMaker\AuctionLot\Common\Access
 */
trait AuctionLotMakerAccessCheckerAwareTrait
{
    /**
     * @var AuctionLotMakerAccessChecker|null
     */
    protected ?AuctionLotMakerAccessChecker $auctionLotMakerAccessChecker = null;

    /**
     * @return AuctionLotMakerAccessChecker
     */
    protected function getAuctionLotMakerAccessChecker(): AuctionLotMakerAccessChecker
    {
        if ($this->auctionLotMakerAccessChecker === null) {
            $this->auctionLotMakerAccessChecker = AuctionLotMakerAccessChecker::new();
        }
        return $this->auctionLotMakerAccessChecker;
    }

    /**
     * @param AuctionLotMakerAccessChecker $auctionLotMakerAccessChecker
     * @return $this
     * @internal
     */
    public function setAuctionLotMakerAccessChecker(AuctionLotMakerAccessChecker $auctionLotMakerAccessChecker): static
    {
        $this->auctionLotMakerAccessChecker = $auctionLotMakerAccessChecker;
        return $this;
    }
}
