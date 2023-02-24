<?php
/**
 * SAM-10974: Stacked Tax. Public My Invoice pages. Extract NMI invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\Nmi\Charge;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ResponsiveInvoiceViewNmiChargingResult
 * @package Sam\Invoice\Common\Charge\Responsive\Gate\Nmi\Charge
 */
class ResponsiveInvoiceViewNmiChargingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVOICE_USER_DELETED = 1;
    public const ERR_CHARGE = 2;
    public const ERR_PAYMENT_METHOD_NOT_MATCHED = 3;
    public const ERR_UPDATE_CIM_INFO = 4;

    protected const ERROR_MESSAGES = [
        self::ERR_INVOICE_USER_DELETED => 'Invoice user deleted',
        self::ERR_CHARGE => 'Bidder card decline or error',
        self::ERR_PAYMENT_METHOD_NOT_MATCHED => 'Payment method does not match',
        self::ERR_UPDATE_CIM_INFO => 'Cannot update CIM info',
    ];

    public float $amount;

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
    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addError($code, $message);
        return $this;
    }

    // --- Query error ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasChargeError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_CHARGE);
    }

    public function hasCimUpdateError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_UPDATE_CIM_INFO);
    }

    /**
     * @return int[]
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(string $glue = "\n"): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    // --- Query success ---

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    // --- Query methods ---

    public function statusMessage(): string
    {
        if ($this->hasError()) {
            return $this->errorMessage();
        }
        return '';
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
