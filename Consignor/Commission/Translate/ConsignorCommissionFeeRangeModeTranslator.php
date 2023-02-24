<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Translate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Helper class for translating consignor commission fee range mode
 *
 * Class ConsignorCommissionFeeRangeModeTranslator
 * @package Sam\Consignor\Commission\Translate
 */
class ConsignorCommissionFeeRangeModeTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    private const TRANSLATION_KEYS = [
        Constants\ConsignorCommissionFee::RANGE_MODE_SUM => 'consignor.commission_fee.range.mode.sum.label',
        Constants\ConsignorCommissionFee::RANGE_MODE_GREATER => 'consignor.commission_fee.range.mode.greater.label'
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
     * @param int $mode
     * @return string
     */
    public function trans(int $mode, array $params = [], string $domain = null, string $language = null): string
    {
        $translation = '';
        $key = self::TRANSLATION_KEYS[$mode] ?? null;
        if ($key) {
            $translation = $this->getAdminTranslator()->trans($key, $params, $domain, $language);
        }
        return $translation;
    }
}
