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
use Sam\Core\Constants\Url;

/**
 * List of valid Public(Responsive) area Url types for which we are using this configuration.
 * @see Url::P_AUCTIONS_ABSENTEE_BIDS,
 * @see Url::P_AUCTIONS_ASK_QUESTION,
 * @see Url::P_AUCTIONS_BIDDING_HISTORY,
 * @see Url::P_AUCTIONS_CONFIRM_BUY,
 * @see Url::P_AUCTIONS_TELL_FRIEND,
 * @see Url::P_LOT_DETAILS_CATALOG_LOT_RESULT,
 * @see Url::P_LOT_ITEM_PREVIEW,
 * @see Url::P_REGISTER_AUCTION_LOT_ITEM_CHANGES,
 * @see Url::P_REGISTER_SPECIAL_TERMS_AND_CONDITIONS
 *
 * Should not be used with
 * @see Url::P_LOT_DETAILS_CATALOG_LOT,
 *
 * Class ResponsiveAuctionLotViewUrlConfig
 * @package Sam\Application\Url\Build\Config\AuctionLot
 */
class AnySingleAuctionLotUrlConfig extends AbstractUrlConfig
{
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
     * @param int $urlType
     * @param int|null $lotItemId - pass null when constructing template url for js
     * @param int|null $auctionId - pass null when constructing template url for js
     * @param array $options = [
     *     ... // regular options
     *     ... // account pre-loading optionals (JIC, not needed now)
     * ]
     * @return static
     */
    public function construct(int $urlType, ?int $lotItemId, ?int $auctionId, array $options = []): static
    {
        $options[UrlConfigConstants::URL_TYPE] = $urlType;
        $options[UrlConfigConstants::PARAMS] = [$auctionId, $lotItemId];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int $urlType
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function forWeb(int $urlType, ?int $lotItemId, ?int $auctionId, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($urlType, $lotItemId, $auctionId, $options);
    }

    /**
     * @param int $urlType
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function forRedirect(int $urlType, ?int $lotItemId, ?int $auctionId, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($urlType, $lotItemId, $auctionId, $options);
    }

    /**
     * @param int $urlType
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function forDomainRule(int $urlType, ?int $lotItemId, ?int $auctionId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($urlType, $lotItemId, $auctionId, $options);
    }

    /**
     * @param int $urlType
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param array $options
     * @return static
     */
    public function forBackPage(int $urlType, ?int $lotItemId, ?int $auctionId, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($urlType, $lotItemId, $auctionId, $options);
    }

    /**
     * @param int $urlType
     * @param array $options
     * @return static
     */
    public function forTemplateByType(int $urlType, array $options = []): static
    {
        $options = $this->toTemplateViewOptions($options);
        return $this->construct($urlType, null, null, $options);
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
