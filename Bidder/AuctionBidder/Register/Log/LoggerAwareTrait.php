<?php

namespace Sam\Bidder\AuctionBidder\Register\Log;

/**
 * Trait LoggerAwareTrait
 * @package Sam\Bidder\AuctionBidder\Register\Log
 */
trait LoggerAwareTrait
{
    /**
     * @var Logger|null
     */
    protected ?Logger $logger = null;

    /**
     * @return Logger
     */
    protected function getLogger(): Logger
    {
        if ($this->logger === null) {
            $this->logger = Logger::new();
        }
        return $this->logger;
    }

    /**
     * @param Logger $logger
     * @return static
     */
    public function setLogger(Logger $logger): static
    {
        $this->logger = $logger;
        return $this;
    }
}
