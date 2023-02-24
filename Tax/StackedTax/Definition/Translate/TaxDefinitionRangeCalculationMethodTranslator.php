<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Definition\Translate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class TaxDefinitionRangeCalculationMethodTranslator
 * @package Sam\Tax\StackedTax\Definition\Translate
 */
class TaxDefinitionRangeCalculationMethodTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function trans(string $method): string
    {
        $translation = '';
        $key = Constants\StackedTax::RANGE_CALCULATION_METHOD_TRANSLATIONS[$method] ?? null;
        if ($key) {
            $translation = $this->getAdminTranslator()->trans($key, [], 'admin_tax_definition');
        }
        return $translation;
    }
}
