<?php
/**
 * SAM-7974: Multiple Consignor commission rates and unsold commission extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\ConsignorCommissionFee;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Constants;

/**
 * Config object for consignor commission fee edit page url
 *
 * Class AdminConsignorCommissionFeeEditUrlConfig
 * @package Sam\Application\Url\Build\Config\ConsignorCommissionFee
 */
class AdminConsignorCommissionFeeEditUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::A_CONSIGNOR_COMMISSION_FEE_EDIT;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Constructors ---

    /**
     * @param int|null $consignorCommissionId - pass null when constructing template url for js
     * @param array $options
     * @return static
     */
    public function construct(?int $consignorCommissionId, array $options = []): static
    {
        $options[UrlConfigConstants::PARAMS] = [$consignorCommissionId];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int|null $consignorCommissionId
     * @param array $options
     * @return static
     */
    public function forWeb(?int $consignorCommissionId, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($consignorCommissionId, $options);
    }

    /**
     * @param int|null $consignorCommissionId
     * @param array $options
     * @return static
     */
    public function forRedirect(?int $consignorCommissionId, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($consignorCommissionId, $options);
    }

    /**
     * @param int|null $consignorCommissionId
     * @param array $options
     * @return static
     */
    public function forDomainRule(?int $consignorCommissionId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($consignorCommissionId, $options);
    }

    /**
     * @param int|null $consignorCommissionId
     * @param array $options
     * @return static
     */
    public function forBackPage(?int $consignorCommissionId, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($consignorCommissionId, $options);
    }
}
