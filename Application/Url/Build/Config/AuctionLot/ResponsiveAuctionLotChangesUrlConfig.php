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

namespace Sam\Application\Url\Build\Config\AuctionLot;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;

class ResponsiveAuctionLotChangesUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::P_REGISTER_AUCTION_LOT_ITEM_CHANGES;

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
     * @param array|null $lotItemIds - pass null when constructing template url for js
     * @param int|null $auctionId - pass null when constructing template url for js
     * @param array $options
     * @return static
     */
    public function construct(?array $lotItemIds, ?int $auctionId, array $options = []): static
    {
        $options[UrlConfigConstants::PARAMS] = [$auctionId, implode(',', (array)$lotItemIds)];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param array|null $lotItemIds
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function forWeb(?array $lotItemIds, ?int $auctionId, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($lotItemIds, $auctionId, $options);
    }

    /**
     * @param array|null $lotItemIds
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function forRedirect(?array $lotItemIds, ?int $auctionId, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($lotItemIds, $auctionId, $options);
    }

    /**
     * @param array|null $lotItemIds
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function forDomainRule(?array $lotItemIds, ?int $auctionId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($lotItemIds, $auctionId, $options);
    }

    /**
     * @param array|null $lotItemIds
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function forBackPage(?array $lotItemIds, ?int $auctionId, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($lotItemIds, $auctionId, $options);
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
     * @return int[]
     */
    public function lotItemIds(): array
    {
        return ArrayCast::castInt(explode(',', $this->readStringParam(1)));
    }
}
