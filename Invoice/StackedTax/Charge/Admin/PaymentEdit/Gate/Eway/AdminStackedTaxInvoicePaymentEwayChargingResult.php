<?php
/**
 * SAM-10912: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Eway invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Eway;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;

/**
 * @package Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Eway
 */
class AdminStackedTaxInvoicePaymentEwayChargingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVOICE_USER_DELETED = 1;
    public const ERR_UPDATE_CIM_INFO = 2;
    public const ERR_CHARGE = 3;

    public const OK_CHARGED = 11;

    protected const ERROR_MESSAGES = [
        self::ERR_INVOICE_USER_DELETED => 'Invoice user deleted',
        self::ERR_UPDATE_CIM_INFO => 'Cannot update CIM info',
        self::ERR_CHARGE => 'CC charge failed',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_CHARGED => 'Charged successfully'
    ];

    public array $noteInfo = [];

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
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addError($code, $message);
        return $this;
    }

    public function addSuccess(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addSuccess($code, $message);
        return $this;
    }

    public function setNoteInfo(string $transactionId, string $ccLast4): static
    {
        $this->noteInfo = [
            'Trans.ID' => $transactionId,
            'CC' => $ccLast4
        ];
        return $this;
    }

    // --- Query error ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
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

    public function logData(): array
    {
        $logData = [];
        if ($this->hasError()) {
            $logData += [
                'error code' => $this->errorCodes(),
                'error message' => $this->errorMessage()
            ];
        }
        return $logData;
    }
}
