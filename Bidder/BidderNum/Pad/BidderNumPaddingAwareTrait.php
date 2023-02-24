<?php
/**
 * SAM-8662: Adjustments for Bidder Number Padding and Adviser services and apply unit tests
 */

namespace Sam\Bidder\BidderNum\Pad;

/**
 * Trait BidderNumPaddingAwareTrait
 * @package Sam\Bidder\BidderNum\Pad
 */
trait BidderNumPaddingAwareTrait
{
    protected ?BidderNumberPaddingInterface $bidderNumberPadding = null;

    /**
     * @return BidderNumberPaddingInterface
     */
    protected function getBidderNumberPadding(): BidderNumberPaddingInterface
    {
        if ($this->bidderNumberPadding === null) {
            $this->bidderNumberPadding = BidderNumberPadding::new();
        }
        return $this->bidderNumberPadding;
    }

    /**
     * @param BidderNumberPaddingInterface $bidderNumPadding
     * @return static
     * @internal
     */
    public function setBidderNumberPadding(BidderNumberPaddingInterface $bidderNumPadding): static
    {
        $this->bidderNumberPadding = $bidderNumPadding;
        return $this;
    }
}
