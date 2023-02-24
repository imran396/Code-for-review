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

/**
 * Helper class for translating consignor commission fee reference
 *
 * Class ConsignorCommissionFeeReferenceTranslator
 * @package Sam\Consignor\Commission\Translate
 */
class ConsignorCommissionFeeReferenceTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    private const TRANSLATION_KEY_TPL = 'consignor.commission_fee.range.fee_reference.%s.label';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $method
     * @return string
     */
    public function trans(string $method, array $params = [], string $domain = null, string $language = null): string
    {
        $key = sprintf(self::TRANSLATION_KEY_TPL, $method);
        $translation = $this->getAdminTranslator()->trans($key, $params, $domain, $language);
        return $translation;
    }
}
