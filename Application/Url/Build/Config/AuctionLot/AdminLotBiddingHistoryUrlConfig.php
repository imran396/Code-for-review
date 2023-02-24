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

namespace Sam\Application\Url\Build\Config\AuctionLot;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Constants;

/**
 * Class AdminLotBiddingHistoryUrlConfig
 * @package Sam\Application\Url\Build\Config
 */
class AdminLotBiddingHistoryUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::A_AUCTIONS_LOT_BID_HISTORY;

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
     * @param int|null $lotItemId
     * @param int|null $auctionId null/0 means unassigned to auction lot details preview page.
     * @param array $options = [
     *     ... // regular options
     *     ... // account pre-loading optionals
     * ]
     * @return static
     */
    public function construct(
        ?int $lotItemId,
        ?int $auctionId = null,
        array $options = []
    ): static {
        $options[UrlConfigConstants::PARAMS] = [$auctionId, $lotItemId];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function forWeb(?int $lotItemId, ?int $auctionId, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($lotItemId, $auctionId, $options);
    }

    /**
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function forRedirect(?int $lotItemId, ?int $auctionId, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($lotItemId, $auctionId, $options);
    }

    /**
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function forDomainRule(?int $lotItemId, ?int $auctionId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($lotItemId, $auctionId, $options);
    }

    /**
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function forBackPage(?int $lotItemId, ?int $auctionId, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($lotItemId, $auctionId, $options);
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
     * @return int|null
     */
    public function lotItemId(): ?int
    {
        return $this->readIntParam(1);
    }
}
