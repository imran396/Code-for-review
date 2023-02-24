<?php
/**
 * This trait for handling optional values related to Auction entity and may be required for url building.
 *
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Auction\Base;

use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;

/**
 * Class OptionalAuctionAdditionalAwareTrait
 * @package Sam\Application\Url
 */
trait OptionalAuctionAdditionalAwareTrait
{
    private ?string $auctionInfoLink = null;

    /**
     * @return string|null
     */
    public function getOptionalAuctionInfoLink(): ?string
    {
        return $this->auctionInfoLink;
    }

    public function setOptionalAuctionInfoLink(string $auctionInfoLink): static
    {
        $this->auctionInfoLink = $auctionInfoLink;
        return $this;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionalAuctionAdditional(array $optionals): void
    {
        $this->auctionInfoLink = $optionals[UrlConfigConstants::OP_AUCTION_INFO_LINK] ?? null;
    }
}
