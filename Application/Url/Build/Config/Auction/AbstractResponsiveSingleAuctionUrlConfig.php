<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Auction;

use Sam\Core\Constants;
use Sam\Application\Url\Build\Config\Auction\Base\OptionalAuctionAdditionalAwareInterface;
use Sam\Application\Url\Build\Config\Auction\Base\OptionalAuctionAdditionalAwareTrait;
use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;

/**
 * We use this config for building url by urlType:
 * @see Constants\Url::P_AUCTIONS_FIRST_LOT
 * @see Constants\Url::P_AUCTIONS_INFO
 * @see Constants\Url::P_AUCTIONS_REGISTER
 * @see Constants\Url::P_AUCTIONS_CATALOG
 * @see Constants\Url::P_AUCTIONS_LIVE_SALE
 *
 * Class AbstractResponsiveSingleAuctionUrlConfig
 * @package Sam\Application\Url\Build\Config
 */
abstract class AbstractResponsiveSingleAuctionUrlConfig extends AbstractUrlConfig
    implements OptionalAuctionAdditionalAwareInterface
{
    use OptionalAuctionAdditionalAwareTrait;

    // --- Constructors ---

    /**
     * @param int|null $auctionId - pass null when constructing template url for js
     * @param string|null $seoUrl - pass null when constructing template url for js or we should detect it with help of service
     * @param array $options = [
     *     ... // regular options
     *     ... // account pre-loading optionals
     *     ... // other auction pre-loading optionals
     * ]
     * @return static
     */
    public function construct(?int $auctionId, ?string $seoUrl = null, array $options = []): static
    {
        $options[UrlConfigConstants::URL_TYPE] = $this->urlType();
        $options[UrlConfigConstants::PARAMS] = [$auctionId, $seoUrl];
        $this->initOptionalAuctionAdditional($options);
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int|null $auctionId
     * @param string|null $seoUrl
     * @param array $options
     * @return static
     */
    public function forWeb(?int $auctionId, ?string $seoUrl = null, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($auctionId, $seoUrl, $options);
    }

    /**
     * @param int|null $auctionId
     * @param string|null $seoUrl
     * @param array $options
     * @return static
     */
    public function forRedirect(?int $auctionId, ?string $seoUrl = null, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($auctionId, $seoUrl, $options);
    }

    /**
     * @param int|null $auctionId
     * @param string|null $seoUrl
     * @param array $options
     * @return static
     */
    public function forDomainRule(?int $auctionId, ?string $seoUrl = null, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($auctionId, $seoUrl, $options);
    }

    /**
     * @param int|null $auctionId
     * @param string|null $seoUrl
     * @param array $options
     * @return static
     */
    public function forBackPage(?int $auctionId, ?string $seoUrl = null, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($auctionId, $seoUrl, $options);
    }

    /**
     * @param AbstractResponsiveSingleAuctionUrlConfig $urlConfig
     * @return static
     */
    public function fromConfig($urlConfig): static
    {
        $new = parent::fromConfig($urlConfig);
        /** Copy auction additional optionals */
        $auctionAdditionalOptionals = [
            UrlConfigConstants::OP_AUCTION_INFO_LINK => $urlConfig->getOptionalAuctionInfoLink(),
        ];
        $this->initOptionalAuctionAdditional($auctionAdditionalOptionals);
        return $new;
    }

    // --- Local query methods ---

    /**
     * @return int|null
     */
    public function auctionId(): ?int
    {
        return $this->readIntParam(0);
    }

    /**
     * @return string|null
     */
    public function seoUrl(): ?string
    {
        return $this->readStringParam(1);
    }

    /**
     * @return bool
     */
    public function hasAuctionInfoLink(): bool
    {
        return (string)$this->getOptionalAuctionInfoLink() !== '';
    }
}
