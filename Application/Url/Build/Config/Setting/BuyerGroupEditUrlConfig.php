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
 * Class BuyerGroupEditUrlConfig
 * @package Sam\Application\Url\Build\Config\SingleInvoice
 */
class BuyerGroupEditUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::A_MANAGE_BUYER_GROUP_EDIT;

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
     * @param int|null $buyerGroupId
     * @param array $options
     * @return static
     */
    public function construct(?int $buyerGroupId, array $options = []): static
    {
        $options[UrlConfigConstants::PARAMS] = [$buyerGroupId];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int|null $buyerGroupId
     * @param array $options
     * @return static
     */
    public function forWeb(?int $buyerGroupId, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($buyerGroupId, $options);
    }

    /**
     * @param int|null $buyerGroupId
     * @param array $options
     * @return static
     */
    public function forRedirect(?int $buyerGroupId, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($buyerGroupId, $options);
    }

    /**
     * @param int|null $buyerGroupId
     * @param array $options
     * @return static
     */
    public function forDomainRule(?int $buyerGroupId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($buyerGroupId, $options);
    }

    /**
     * @param int|null $buyerGroupId
     * @param array $options
     * @return static
     */
    public function forBackPage(?int $buyerGroupId, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($buyerGroupId, $options);
    }

    // --- Local query methods ---

    /**
     * @return int|null
     */
    public function buyerGroupId(): ?int
    {
        return $this->readIntParam(0);
    }

}
