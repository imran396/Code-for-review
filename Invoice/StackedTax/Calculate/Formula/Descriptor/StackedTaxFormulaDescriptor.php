<?php
/**
 * SAM-10770: Explain invoice calculation formula in support log
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 11, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Calculate\Formula\Descriptor;

use JetBrains\PhpStorm\Pure;
use Sam\Core\Service\CustomizableClass;

/**
 * Class FormulaDescriptor
 * @package Sam\Invoice\Legacy\Calculate\Formula\Descriptor
 */
class StackedTaxFormulaDescriptor extends CustomizableClass
{
    public ?float $value;
    public string $key;
    public string $formula;
    public array $clarifications;

    public const KEY_BALANCE_DUE = 'BalanceDue';
    public const KEY_GRAND_TOTAL = 'GrandTotal';
    public const KEY_TOTAL = 'Total';
    public const KEY_SHIPPING = 'Shipping';
    public const KEY_TOTAL_CHARGES = 'TotalCharges';
    public const KEY_TOTAL_SALES_TAX_APPLIED = 'TotalSalesTaxApplied';
    public const KEY_ITEM_TPL = 'Item%d';
    public const KEY_BUYER_TAX_SERVICE = 'BuyerTaxService'; // TODO: search for better name
    public const KEY_CASH_DISCOUNT = 'CashDiscount';
    public const KEY_TOTAL_PAYMENT = 'TotalPayment';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(?float $value, string $key, string $formula, array $clarifications = []): static
    {
        $this->value = $value;
        $this->key = $key;
        $this->formula = $formula;
        $this->clarifications = $clarifications;
        return $this;
    }

    #[Pure] public function toArray(): array
    {
        return [
            $this->value,
            $this->key,
            $this->formula,
            $this->clarifications
        ];
    }
}
