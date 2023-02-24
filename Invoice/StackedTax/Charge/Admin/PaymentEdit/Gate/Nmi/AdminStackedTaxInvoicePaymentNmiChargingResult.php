<?php
/**
 * <Description of class>
 *
 * SAM-10919: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Nmi invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Nmi;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

class AdminStackedTaxInvoicePaymentNmiChargingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVOICE_USER_DELETED = 1;
    public const ERR_CARD_INFO_NOT_FOUND = 2;
    public const ERR_BIDDER_CARD = 3;
    public const ERR_UPDATE_CIM_INFO = 4;
    public const ERR_INVALID_CREDIT_CARD = 5;

    protected const ERROR_MESSAGES = [
        self::ERR_INVOICE_USER_DELETED => 'Invoice user deleted',
        self::ERR_CARD_INFO_NOT_FOUND => 'Bidder has no CIM and has no credit card info',
        self::ERR_BIDDER_CARD => 'Bidder card decline or error',
        self::ERR_UPDATE_CIM_INFO => 'Cannot update CIM info',
        self::ERR_INVALID_CREDIT_CARD => 'invalid credit card',
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
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addError($code, $message);
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
}
