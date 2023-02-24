<?php
/**
 * SAM-9966: Optimize db queries for Public/Admin Invoice List/Edit
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\Invoice\Tax;

use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceTaxCalculationAmountDto
 * @package Sam\Invoice\Legacy\Calculate\Tax
 */
class InvoiceItemTaxCalculationAmountsDto extends CustomizableClass
{
    public float $hammerPrice = 0.;
    public float $buyersPremium = 0.;
    public float $salesTax = 0.;
    public ?int $taxApplication = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param float $hammerPrice
     * @param float $buyersPremium
     * @param float $salesTax
     * @param int $taxApplication
     * @return $this
     */
    public function construct(
        float $hammerPrice,
        float $buyersPremium,
        float $salesTax,
        int $taxApplication
    ): static {
        $this->hammerPrice = $hammerPrice;
        $this->buyersPremium = $buyersPremium;
        $this->salesTax = $salesTax;
        $this->taxApplication = $taxApplication;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (float)$row['hammer_price'],
            (float)$row['buyers_premium'],
            (float)$row['sales_tax'],
            (int)$row['tax_application']
        );
    }

    public function logData(): array
    {
        return [
            'hp' => $this->hammerPrice,
            'bp' => $this->buyersPremium,
            'tax percent' => $this->salesTax,
            'tax application' => $this->taxApplication,
        ];
    }
}
