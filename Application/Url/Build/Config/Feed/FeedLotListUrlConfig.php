<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Feed;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Constants;

/**
 * Class FeedEditUrlConfig
 * @package Sam\Application\Url\Build\Config\SingleInvoice
 */
class FeedLotListUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::P_FEED_LOTS;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Constructors ---

    /**
     * @param string|null $slug
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function construct(?string $slug, ?int $auctionId, array $options = []): static
    {
        $options[UrlConfigConstants::PARAMS] = [$slug, $auctionId];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param string|null $slug
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function forWeb(?string $slug, ?int $auctionId, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($slug, $auctionId, $options);
    }

    /**
     * @param string|null $slug
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function forRedirect(?string $slug, ?int $auctionId, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($slug, $auctionId, $options);
    }

    /**
     * @param string|null $slug
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function forDomainRule(?string $slug, ?int $auctionId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($slug, $auctionId, $options);
    }

    /**
     * @param string|null $slug
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function forBackPage(?string $slug, ?int $auctionId, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($slug, $auctionId, $options);
    }

    // --- Local query methods ---

    /**
     * @return string|null
     */
    public function slug(): ?string
    {
        return $this->readStringParam(0);
    }

    /**
     * @return int|null
     */
    public function auctionId(): ?int
    {
        return $this->readIntParam(1);
    }
}
