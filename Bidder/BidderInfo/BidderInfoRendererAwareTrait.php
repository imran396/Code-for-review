<?php

namespace Sam\Bidder\BidderInfo;

/**
 * Trait BidderInfoRendererAwareTrait
 * @package Sam\Bidder\BidderInfo
 */
trait BidderInfoRendererAwareTrait
{
    /**
     * @var BidderInfoRenderer|null
     */
    protected ?BidderInfoRenderer $bidderInfoRenderer = null;

    /**
     * @param BidderInfoRenderer $bidderInfoRenderer
     * @return static
     * @internal
     */
    public function setBidderInfoRenderer(BidderInfoRenderer $bidderInfoRenderer): static
    {
        $this->bidderInfoRenderer = $bidderInfoRenderer;
        return $this;
    }

    /**
     * @return BidderInfoRenderer
     */
    protected function getBidderInfoRenderer(): BidderInfoRenderer
    {
        if ($this->bidderInfoRenderer === null) {
            $this->bidderInfoRenderer = BidderInfoRenderer::new();
        }
        return $this->bidderInfoRenderer;
    }
}
