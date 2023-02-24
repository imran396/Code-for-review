<?php
/**
 * TODO: Not sure yet, if we need this factory for distinguishing NumberFormatter
 *
 * SAM-10339: Fetch US_NUMBER_FORMATTING and KEEP_DECIMAL_INVOICE from entity accounts
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Transform\Number;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class NumberFormatterFactory
 * @package Sam\Transform\Number
 */
class NumberFormatterFactory extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $serviceAccountId
     * @param bool $shouldAddDecimalZerosForInteger
     * @return NumberFormatterInterface
     */
    public function create(int $serviceAccountId, bool $shouldAddDecimalZerosForInteger = false): NumberFormatterInterface
    {
        if ($this->cfg()->get('core->general->number->formatter') === Constants\NumberFormat::LEGACY) {
            $numberFormatter = NumberFormatter::new();
        } else { // 'locale'
            $numberFormatter = NextNumberFormatter::new();
        }
        $numberFormatter->construct($serviceAccountId, $shouldAddDecimalZerosForInteger);
        return $numberFormatter;
    }

    public function createForInvoice(int $serviceAccountId): NumberFormatterInterface
    {
        return $this->create($serviceAccountId, true);
    }

    public function createForSettlement(int $serviceAccountId): NumberFormatterInterface
    {
        return $this->create($serviceAccountId, true);
    }
}
