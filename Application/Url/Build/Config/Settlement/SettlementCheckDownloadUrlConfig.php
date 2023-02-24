<?php
/**
 * This url is used to display image of Settlement Check at page.
 *
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 12, 2021
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
 * Class SettlementCheckUrlConfig
 * @package Sam\Application\Url\Build\Config\Settlement
 */
class SettlementCheckDownloadUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::P_SETTLEMENT_CHECK_DOWNLOAD;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $accountId
     * @param array $options
     * @return $this
     */
    public function construct(?int $accountId, array $options = []): static
    {
        $options[UrlConfigConstants::PARAMS] = [$accountId];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int|null $accountId
     * @param array $options
     * @return static
     */
    public function forWeb(?int $accountId, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($accountId, $options);
    }

    /**
     * @param int|null $accountId
     * @param array $options
     * @return static
     */
    public function forRedirect(?int $accountId, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($accountId, $options);
    }

    /**
     * @param int|null $accountId
     * @param array $options
     * @return static
     */
    public function forDomainRule(?int $accountId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($accountId, $options);
    }

    /**
     * @param int|null $accountId
     * @param array $options
     * @return static
     */
    public function forBackPage(?int $accountId, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($accountId, $options);
    }

    // --- Local query methods ---

    /**
     * @return int|null
     */
    public function accountId(): ?int
    {
        return $this->readIntParam(0);
    }

}
