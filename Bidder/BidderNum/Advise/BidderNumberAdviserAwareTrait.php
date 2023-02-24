<?php
/**
 * SAM-8662: Adjustments for Bidder Number Padding and Adviser services and apply unit tests
 */

namespace Sam\Bidder\BidderNum\Advise;

/**
 * Trait BidderNumAdviserAwareTrait
 * @package Sam\Bidder\BidderNum
 */
trait BidderNumberAdviserAwareTrait
{
    /**
     * @var BidderNumberAdviser|null
     */
    protected ?BidderNumberAdviser $bidderNumberAdviser = null;

    /**
     * @return BidderNumberAdviser
     */
    protected function getBidderNumberAdviser(): BidderNumberAdviser
    {
        if ($this->bidderNumberAdviser === null) {
            $this->bidderNumberAdviser = BidderNumberAdviser::new();
        }
        return $this->bidderNumberAdviser;
    }

    /**
     * @param BidderNumberAdviser $adviser
     * @return static
     * @internal
     */
    public function setBidderNumberAdviser(BidderNumberAdviser $adviser): static
    {
        $this->bidderNumberAdviser = $adviser;
        return $this;
    }
}
