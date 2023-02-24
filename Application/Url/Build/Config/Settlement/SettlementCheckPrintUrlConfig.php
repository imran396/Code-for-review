<?php
/**
 * SAM-9890: Check Printing for Settlements: Implementation of printing content rendering
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Settlement;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Constants;

/**
 * Config object for settlement checks print page url
 *
 * Class SettlementCheckPrintUrlConfig
 * @package Sam\Application\Url\Build\Config\Settlement
 */
class SettlementCheckPrintUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::A_MANAGE_SETTLEMENT_CHECK_PRINT;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $checkIds
     * @param array $options
     * @return $this
     */
    public function construct(array $checkIds, array $options = []): static
    {
        $options[UrlConfigConstants::PARAMS] = [implode(',', $checkIds)];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param array $checkIds
     * @param array $options
     * @return static
     */
    public function forWeb(array $checkIds, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($checkIds, $options);
    }

    /**
     * @param array $checkIds
     * @param array $options
     * @return static
     */
    public function forRedirect(array $checkIds, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($checkIds, $options);
    }

    /**
     * @param array $checkIds
     * @param array $options
     * @return static
     */
    public function forDomainRule(array $checkIds, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($checkIds, $options);
    }

    /**
     * @param array $checkIds
     * @param array $options
     * @return static
     */
    public function forBackPage(array $checkIds, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($checkIds, $options);
    }
}
