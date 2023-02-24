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

namespace Sam\Application\Url\Build\Config\Setting;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Constants;

/**
 * Class AuctionCustomFieldEditUrlConfig
 * @package Sam\Application\Url\Build\Config\SingleInvoice
 */
class AuctionCustomFieldEditUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::A_MANAGE_CUSTOM_FIELD_EDIT_AUCTION;

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
     * @param int|null $auctionCustomFieldId
     * @param array $options
     * @return static
     */
    public function construct(?int $auctionCustomFieldId, array $options = []): static
    {
        $options[UrlConfigConstants::PARAMS] = [$auctionCustomFieldId];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int|null $auctionCustomFieldId
     * @param array $options
     * @return static
     */
    public function forWeb(?int $auctionCustomFieldId, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($auctionCustomFieldId, $options);
    }

    /**
     * @param int|null $auctionCustomFieldId
     * @param array $options
     * @return static
     */
    public function forRedirect(?int $auctionCustomFieldId, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($auctionCustomFieldId, $options);
    }

    /**
     * @param int|null $auctionCustomFieldId
     * @param array $options
     * @return static
     */
    public function forDomainRule(?int $auctionCustomFieldId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($auctionCustomFieldId, $options);
    }

    /**
     * @param int|null $auctionCustomFieldId
     * @param array $options
     * @return static
     */
    public function forBackPage(?int $auctionCustomFieldId, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($auctionCustomFieldId, $options);
    }

    // --- Local query methods ---

    /**
     * @return int|null
     */
    public function auctionCustomFieldId(): ?int
    {
        return $this->readIntParam(0);
    }

}
