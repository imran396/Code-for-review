<?php
/**
 * SAM-10915: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Authorize.Net invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

class AdminStackedTaxInvoicePaymentAuthorizeNetChargingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVOICE_USER_DELETED = 1;
    public const ERR_UPDATE_CC_INFO = 2;
    public const ERR_NO_CC_INFO = 3;
    public const ERR_INVALID_CREDIT_CARD = 4;
    public const ERR_CHARGE = 5;

    public const OK_CHARGED = 11;

    protected const ERROR_MESSAGES = [
        self::ERR_INVOICE_USER_DELETED => 'Invoice user deleted',
        self::ERR_UPDATE_CC_INFO => 'Cannot update CC info',
        self::ERR_NO_CC_INFO => 'Bidder has no CIM and has no credit card info',
        self::ERR_INVALID_CREDIT_CARD => 'Invalid credit card',
        self::ERR_CHARGE => 'CC charge failed',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_CHARGED => 'Charged successfully'
    ];

    public array $noteInfo = [];
    public bool $isHeldForReview = false;

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

    public function setNoteInfo(array $noteInfo): static
    {
        $this->noteInfo = $noteInfo;
        return $this;
    }

    public function enableHeldForReview(bool $enable): static
    {
        $this->isHeldForReview = $enable;
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

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage("\n");
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

    public function statusCode(): ?int
    {
        if ($this->getResultStatusCollector()->hasSuccess()) {
            return $this->getResultStatusCollector()->getFirstSuccessCode();
        }
        if ($this->hasError()) {
            return $this->getResultStatusCollector()->getFirstErrorCode();
        }
        return null;
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
