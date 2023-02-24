<?php
/**
 * SAM-9795: Check Printing for Settlements: Implementation of html layout and view layer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           10-26, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Application\Url\Build\Config\Settlement;


use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Constants;

/**
 * Class SettlementCheckEditUrlConfig
 * @package Sam\Application\Url\Build\Config\Settlement
 */
class SettlementCheckEditUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::A_MANAGE_SETTLEMENT_CHECK_EDIT;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $settlementCheckId
     * @param array $options
     * @return $this
     */
    public function construct(?int $settlementCheckId, array $options = []): static
    {
        $options[UrlConfigConstants::PARAMS] = [$settlementCheckId];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int|null $settlementCheckId
     * @param array $options
     * @return static
     */
    public function forWeb(?int $settlementCheckId, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($settlementCheckId, $options);
    }

    /**
     * @param int|null $settlementCheckId
     * @param array $options
     * @return static
     */
    public function forRedirect(?int $settlementCheckId, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($settlementCheckId, $options);
    }

    /**
     * @param int|null $settlementCheckId
     * @param array $options
     * @return static
     */
    public function forDomainRule(?int $settlementCheckId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($settlementCheckId, $options);
    }

    /**
     * @param int|null $settlementCheckId
     * @param array $options
     * @return static
     */
    public function forBackPage(?int $settlementCheckId, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($settlementCheckId, $options);
    }

    // --- Local query methods ---

    /**
     * @return int|null
     */
    public function settlementCheckId(): ?int
    {
        return $this->readIntParam(0);
    }
}
