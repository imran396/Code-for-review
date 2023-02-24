<?php

namespace Sam\Bidder\AuctionBidder\Register\Config;

/**
 * Trait ContextAwareTrait
 * @package Sam\Bidder\AuctionBidder\Register\Config
 */
trait ConfigAwareTrait
{
    /**
     * @var Config|null
     */
    protected ?Config $config = null;

    /**
     * @return Config
     */
    protected function getConfig(): Config
    {
        if ($this->config === null) {
            $this->config = Config::new();
        }
        return $this->config;
    }

    /**
     * @param Config $config
     * @return static
     */
    public function setConfig(Config $config): static
    {
        $this->config = $config;
        return $this;
    }
}
