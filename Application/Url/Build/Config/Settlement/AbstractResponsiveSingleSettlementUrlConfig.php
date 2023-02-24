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

namespace Sam\Application\Url\Build\Config\Settlement;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;

/**
 * Class AbstractResponsiveSingleAuctionUrlConfig
 * @package Sam\Application\Url\Build\Config
 */
abstract class AbstractResponsiveSingleSettlementUrlConfig extends AbstractUrlConfig
{
    // --- Constructors ---

    /**
     * @param int|null $settlementId - pass null when constructing template url for js
     * @param array $options = [
     *     ... // regular options
     *     ... // account pre-loading optionals
     * ]
     * @return static
     */
    public function construct(?int $settlementId, array $options = []): static
    {
        $options[UrlConfigConstants::URL_TYPE] = $this->urlType();
        $options[UrlConfigConstants::PARAMS] = [$settlementId];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int|null $settlementId
     * @param array $options
     * @return static
     */
    public function forWeb(?int $settlementId, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($settlementId, $options);
    }

    /**
     * @param int|null $settlementId
     * @param array $options
     * @return static
     */
    public function forRedirect(?int $settlementId, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($settlementId, $options);
    }

    /**
     * @param int|null $settlementId
     * @param array $options
     * @return static
     */
    public function forDomainRule(?int $settlementId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($settlementId, $options);
    }

    /**
     * @param int|null $settlementId
     * @param array $options
     * @return static
     */
    public function forBackPage(?int $settlementId, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($settlementId, $options);
    }

    // --- Local query methods ---

    /**
     * @return int|null
     */
    public function settlementId(): ?int
    {
        return $this->readIntParam(0);
    }
}
