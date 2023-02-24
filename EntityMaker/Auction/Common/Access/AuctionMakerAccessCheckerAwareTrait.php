<?php
/**
 * SAM-8841: User entity-maker module structural adjustments for v3-5
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

namespace Sam\EntityMaker\Auction\Common\Access;

/**
 * Trait AuctionMakerAccessCheckerAwareTrait
 * @package Sam\EntityMaker\Auction\Common\Access
 */
trait AuctionMakerAccessCheckerAwareTrait
{
    /**
     * @var AuctionMakerAccessChecker|null
     */
    protected ?AuctionMakerAccessChecker $auctionMakerAccessChecker = null;

    /**
     * @return AuctionMakerAccessChecker
     */
    protected function getAuctionMakerAccessChecker(): AuctionMakerAccessChecker
    {
        if ($this->auctionMakerAccessChecker === null) {
            $this->auctionMakerAccessChecker = AuctionMakerAccessChecker::new();
        }
        return $this->auctionMakerAccessChecker;
    }

    /**
     * @param AuctionMakerAccessChecker $auctionMakerAccessChecker
     * @return $this
     * @internal
     */
    public function setAuctionMakerAccessChecker(AuctionMakerAccessChecker $auctionMakerAccessChecker): static
    {
        $this->auctionMakerAccessChecker = $auctionMakerAccessChecker;
        return $this;
    }
}
