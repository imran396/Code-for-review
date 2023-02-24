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

namespace Sam\Application\Url\Build\Config\AuctionBidder;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Constants;

/**
 * Class AdminAuctionBidderAbsenteeLotUrlConfig
 * @package Sam\Application\Url\Build\Config
 */
class AdminAuctionBidderAbsenteeLotUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::A_AUCTIONS_BIDDERS_ABSENTEE_LOT;

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
     * @param int|null $auctionId null/0 means unassigned to auction lot details preview page.
     * @param int|null $userId
     * @param int|null $lotNum
     * @param string|null $lotNumExtension
     * @param string|null $lotNumPrefix
     * @param array $options = [
     *     ... // regular options
     *     ... // account pre-loading optionals
     * ]
     * @return static
     */
    public function construct(
        ?int $auctionId,
        ?int $userId,
        ?int $lotNum,
        ?string $lotNumExtension,
        ?string $lotNumPrefix,
        array $options = []
    ): static {
        $options[UrlConfigConstants::PARAMS] = [$auctionId, $userId, $lotNum, $lotNumPrefix, $lotNumExtension];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int|null $auctionId
     * @param int|null $userId
     * @param int|null $lotNum
     * @param string|null $lotNumExtension
     * @param string|null $lotNumPrefix
     * @param array $options
     * @return static
     */
    public function forWeb(
        ?int $auctionId,
        ?int $userId,
        ?int $lotNum,
        ?string $lotNumExtension,
        ?string $lotNumPrefix,
        array $options = []
    ): static {
        $options = $this->toWebViewOptions($options);
        return $this->construct($auctionId, $userId, $lotNum, $lotNumExtension, $lotNumPrefix, $options);
    }

    /**
     * @param int|null $auctionId
     * @param int|null $userId
     * @param int|null $lotNum
     * @param string|null $lotNumExtension
     * @param string|null $lotNumPrefix
     * @param array $options
     * @return static
     */
    public function forRedirect(
        ?int $auctionId,
        ?int $userId,
        ?int $lotNum,
        ?string $lotNumExtension,
        ?string $lotNumPrefix,
        array $options = []
    ): static {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($auctionId, $userId, $lotNum, $lotNumExtension, $lotNumPrefix, $options);
    }

    /**
     * @param int|null $auctionId
     * @param int|null $userId
     * @param int|null $lotNum
     * @param string|null $lotNumExtension
     * @param string|null $lotNumPrefix
     * @param array $options
     * @return static
     */
    public function forDomainRule(
        ?int $auctionId,
        ?int $userId,
        ?int $lotNum,
        ?string $lotNumExtension,
        ?string $lotNumPrefix,
        array $options = []
    ): static {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($auctionId, $userId, $lotNum, $lotNumExtension, $lotNumPrefix, $options);
    }

    /**
     * @param int|null $auctionId
     * @param int|null $userId
     * @param int|null $lotNum
     * @param string|null $lotNumExtension
     * @param string|null $lotNumPrefix
     * @param array $options
     * @return static
     */
    public function forBackPage(
        ?int $auctionId,
        ?int $userId,
        ?int $lotNum,
        ?string $lotNumExtension,
        ?string $lotNumPrefix,
        array $options = []
    ): static {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($auctionId, $userId, $lotNum, $lotNumExtension, $lotNumPrefix, $options);
    }

    /**
     * Partial template url view will contain only defined parameters, but others are %s placeholders
     * @param int|null $auctionId
     * @param int|null $userId
     * @param int|null $lotNum
     * @param string|null $lotNumExtension
     * @param string|null $lotNumPrefix
     * @param array $options
     * @return static
     */
    public function forPartialTemplate(
        ?int $auctionId = null,
        ?int $userId = null,
        ?int $lotNum = null,
        ?string $lotNumExtension = null,
        ?string $lotNumPrefix = null,
        array $options = []
    ): static {
        $options = $this->toTemplateViewOptions($options);
        return $this->construct($auctionId, $userId, $lotNum, $lotNumExtension, $lotNumPrefix, $options);
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
    public function userId(): ?int
    {
        return $this->readIntParam(1);
    }

    /**
     * @return int|null
     */
    public function lotNum(): ?int
    {
        return $this->readIntParam(2);
    }

    /**
     * @return string|null
     */
    public function lotNumExtension(): ?string
    {
        return $this->readStringParam(4);
    }

    /**
     * @return string|null
     */
    public function lotNumPrefix(): ?string
    {
        return $this->readStringParam(3);
    }
}
