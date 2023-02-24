<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Translate;

use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\Core\Constants;

/**
 * Helper class for translating consignor commission fee calculation method
 *
 * Class ConsignorCommissionFeeCalculationMethodTranslator
 * @package Sam\Consignor\Commission\Translate
 */
class ConsignorCommissionFeeCalculationMethodTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    private const TRANSLATION_KEYS = [
        Constants\ConsignorCommissionFee::CALCULATION_METHOD_SLIDING => 'consignor.commission_fee.calculation_method.sliding.label',
        Constants\ConsignorCommissionFee::CALCULATION_METHOD_TIERED => 'consignor.commission_fee.calculation_method.tiered.label'
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $method
     * @return string
     */
    public function trans(int $method, array $params = [], string $domain = null, string $language = null): string
    {
        $translation = '';
        $key = self::TRANSLATION_KEYS[$method] ?? null;
        if ($key) {
            $translation = $this->getAdminTranslator()->trans($key, $params, $domain, $language);
        }
        return $translation;
    }
}
