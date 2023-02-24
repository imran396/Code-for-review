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
 * Class SettlementCheckCreateUrlConfig
 * @package Sam\Application\Url\Build\Config\Settlement
 */
class SettlementCheckCreateUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::A_MANAGE_SETTLEMENT_CHECK_CREATE;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $settlementId
     * @param array $options
     * @return $this
     */
    public function construct(?int $settlementId, array $options = []): static
    {
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

    public function settlementId(): ?int
    {
        return $this->readIntParam(0);
    }
}
