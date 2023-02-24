<?php
/**
 * SAM-9888: Check Printing for Settlements: Bulk Checks Processing - Account level, Settlements List level (Part 2)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Settlement;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Core\Constants;

/**
 * Config object for all settlement check in account list page url
 *
 * Class SettlementCheckListAllUrlConfig
 * @package Sam\Application\Url\Build\Config\Settlement
 */
class SettlementCheckListAllUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::A_MANAGE_SETTLEMENT_CHECK_LIST_ALL;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $options
     * @return $this
     */
    public function construct(array $options = []): static
    {
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param array $options
     * @return static
     */
    public function forWeb(array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($options);
    }

    /**
     * @param array $options
     * @return static
     */
    public function forRedirect(array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($options);
    }

    /**
     * @param array $options
     * @return static
     */
    public function forDomainRule(array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($options);
    }

    /**
     * @param array $options
     * @return static
     */
    public function forBackPage(array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($options);
    }
}
