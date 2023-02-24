<?php
/**
 * SAM-6769: Refactor invoice applicable sale tax calculation logic and cover it with unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Calculate\SaleTax;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class InvoiceApplicableSaleTaxResult
 * @package Sam\Invoice\Legacy\Calculate\SaleTax
 */
class LegacyInvoiceApplicableSaleTaxResult extends CustomizableClass
{
    /**
     * Sales Tax Percent value
     * @var float
     */
    public float $percent;
    /**
     * Sales Tax Application source
     * @var int
     */
    public int $application;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param float $percent
     * @param int $application
     * @return $this
     */
    public function construct(float $percent, int $application): static
    {
        $this->percent = $percent;
        $this->application = $application;
        return $this;
    }

    /**
     * @return array
     */
    public function logData(): array
    {
        return [
            'sales tax percent' => $this->percent,
            'sales tax application source' => Constants\User::TAX_APPLICATION_NAMES[$this->application] ?? '',
        ];
    }
}
