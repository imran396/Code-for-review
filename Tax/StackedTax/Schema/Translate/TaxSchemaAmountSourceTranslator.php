<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Translate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class TaxSchemaAmountSourceTranslator
 * @package Sam\Tax\StackedTax\Schema\Translate
 */
class TaxSchemaAmountSourceTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATIONS = [
        Constants\StackedTax::AS_HAMMER_PRICE => 'amount_source.hammer_price',
        Constants\StackedTax::AS_BUYERS_PREMIUM => 'amount_source.buyers_premium',
        Constants\StackedTax::AS_SERVICES => 'amount_source.services',
    ];
    protected const TRANSLATION_DOMAIN = 'admin_tax_schema';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function trans(int $amountSource): string
    {
        $key = self::TRANSLATIONS[$amountSource] ?? (string)$amountSource;
        return $this->getAdminTranslator()->trans($key, [], self::TRANSLATION_DOMAIN);
    }
}
