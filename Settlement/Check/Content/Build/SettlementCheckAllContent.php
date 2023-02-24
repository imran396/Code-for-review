<?php
/**
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           10-30, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Build;

use Sam\Core\Service\CustomizableClass;

/**
 * @package Sam\Settlement\Check
 */
class SettlementCheckAllContent extends CustomizableClass
{
    public readonly string $address;
    public readonly string $payee;
    public readonly string $memo;
    public readonly string $amount;
    public readonly string $amountSpelling;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $address,
        string $payee,
        string $memo,
        string $amount,
        string $amountSpelling
    ): static {
        $this->address = $address;
        $this->payee = $payee;
        $this->memo = $memo;
        $this->amount = $amount;
        $this->amountSpelling = $amountSpelling;
        return $this;
    }
}
