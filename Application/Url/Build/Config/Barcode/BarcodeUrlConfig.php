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

namespace Sam\Application\Url\Build\Config\Barcode;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Constants;

/**
 * Class SoundUrlConfig
 * @package Sam\Application\Url\Build\Config
 */
class BarcodeUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::P_BARCODE_IMG;

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
     * @param string|null $barcodeValue Barcode value as is. Url-encode it inside.
     * @param int|null $barcodeType
     * @param string|null $renderMode
     * @param array $options
     * @return $this
     */
    public function construct(?string $barcodeValue, ?int $barcodeType, ?string $renderMode = '', array $options = []): static
    {
        $barcodeValue = urlencode($barcodeValue);
        $options[UrlConfigConstants::PARAMS] = [$barcodeValue, $barcodeType, $renderMode];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param string|null $barcodeValue
     * @param int|null $barcodeType
     * @param string|null $renderMode
     * @param array $options
     * @return $this
     */
    public function forWeb(?string $barcodeValue, ?int $barcodeType, ?string $renderMode = '', array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($barcodeValue, $barcodeType, $renderMode, $options);
    }

    /**
     * @param string|null $barcodeValue
     * @param int|null $barcodeType
     * @param string|null $renderMode
     * @param array $options
     * @return $this
     */
    public function forRedirect(?string $barcodeValue, ?int $barcodeType, ?string $renderMode = '', array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($barcodeValue, $barcodeType, $renderMode, $options);
    }

    /**
     * @param string|null $barcodeValue
     * @param int|null $barcodeType
     * @param string|null $renderMode
     * @param array $options
     * @return $this
     */
    public function forDomainRule(?string $barcodeValue, ?int $barcodeType, ?string $renderMode = '', array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($barcodeValue, $barcodeType, $renderMode, $options);
    }

    /**
     * @param string|null $barcodeValue
     * @param int|null $barcodeType
     * @param string|null $renderMode
     * @param array $options
     * @return $this
     */
    public function forBackPage(?string $barcodeValue, ?int $barcodeType, ?string $renderMode = '', array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($barcodeValue, $barcodeType, $renderMode, $options);
    }

    // --- Local query methods ---

    /**
     * @return string|null
     */
    public function barcodeValue(): ?string
    {
        return $this->readStringParam(0);
    }

    /**
     * @return int|null
     */
    public function barcodeType(): ?int
    {
        return $this->readIntParam(1);
    }

    /**
     * @return string|null
     */
    public function renderMode(): ?string
    {
        return $this->readStringParam(2);
    }
}
