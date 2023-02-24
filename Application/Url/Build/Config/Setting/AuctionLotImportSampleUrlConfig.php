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

use Sam\Core\Constants;
use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;

/**
 * Class ImportSampleUrlConfig
 * @package Sam\Application\Url\Build\Config\SingleInvoice
 */
class AuctionLotImportSampleUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::A_AUCTIONS_LOT_IMPORT_SAMPLE;

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
     * @param int|null $sampleId
     * @param int|null $accountId
     * @param array $options
     * @return static
     */
    public function construct(?int $sampleId, ?int $accountId, array $options = []): static
    {
        $options[UrlConfigConstants::PARAMS] = [$sampleId, $accountId];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int|null $sampleId
     * @param int|null $accountId
     * @param array $options
     * @return static
     */
    public function forWeb(?int $sampleId, ?int $accountId, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($sampleId, $accountId, $options);
    }

    /**
     * @param int|null $sampleId
     * @param int|null $accountId
     * @param array $options
     * @return static
     */
    public function forRedirect(?int $sampleId, ?int $accountId, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($sampleId, $accountId, $options);
    }

    /**
     * @param int|null $sampleId
     * @param int|null $accountId
     * @param array $options
     * @return static
     */
    public function forDomainRule(?int $sampleId, ?int $accountId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($sampleId, $accountId, $options);
    }

    /**
     * @param int|null $sampleId
     * @param int|null $accountId
     * @param array $options
     * @return static
     */
    public function forBackPage(?int $sampleId, ?int $accountId, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($sampleId, $accountId, $options);
    }

    // --- Local query methods ---

    /**
     * @return int|null
     */
    public function sampleId(): ?int
    {
        return $this->readIntParam(0);
    }

    /**
     * @return int|null
     */
    public function accountId(): ?int
    {
        return $this->readIntParam(1);
    }
}
