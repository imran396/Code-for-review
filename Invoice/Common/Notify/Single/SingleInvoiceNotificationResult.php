<?php
/**
 * SAM-11778: Refactor Invoice Notifier for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 21, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Notify\Single;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use User;

/**
 * Class SingleInvoiceNotificationResult
 * @package Sam\Invoice\Common\Notify\Single
 */
class SingleInvoiceNotificationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_BIDDER_USER_NOT_FOUND = 1;
    public const ERR_BIDDER_EMAIL_ABSENT = 2;
    public const ERR_NOT_ACTIVE_USER = 3;
    public const ERR_LOCKED = 4;

    public const OK_NOTIFIED = 11;

    protected const ERROR_MESSAGES = [
        self::ERR_BIDDER_USER_NOT_FOUND => 'User with ID "%d" not found, hence he doesn\'t have an email address so invoice # %d couldn\'t be sent!',
        self::ERR_BIDDER_EMAIL_ABSENT => '%s doesn\'t have an email address so invoice # %d couldn\'t be sent!',
        self::ERR_NOT_ACTIVE_USER => 'Invoice email has not been sent (user is not active)!',
        self::ERR_LOCKED => 'Invoice email has not been sent (locked for action)!',
    ];
    protected const SUCCESS_MESSAGES = [
        self::OK_NOTIFIED => 'Invoice email has been sent!'
    ];

    public int $editorUserId;
    public ?Invoice $invoice;
    public ?User $invoiceUser;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(string $glue = "\n"): self
    {
        $this->getResultStatusCollector()->construct(
            self::ERROR_MESSAGES,
            self::SUCCESS_MESSAGES,
            [],
            [],
            $glue
        );
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code, ?string $message = null): self
    {
        $this->getResultStatusCollector()->addError($code, $message);
        return $this;
    }

    public function addSuccess(int $code, ?string $message = null): self
    {
        $this->getResultStatusCollector()->addSuccess($code, $message);
        return $this;
    }

    public function setInvoice(?Invoice $invoice): self
    {
        $this->invoice = $invoice;
        return $this;
    }

    public function setInvoiceUser(?User $invoiceUser): self
    {
        $this->invoiceUser = $invoiceUser;
        return $this;
    }

    public function setEditorUserId(int $id)
    {
        $this->editorUserId = $id;
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
        return $this->getResultStatusCollector()->hasSuccess();
    }

    /**
     * @return int[]
     */
    public function successCodes(): array
    {
        return $this->getResultStatusCollector()->getSuccessCodes();
    }

    public function successMessage(string $glue = "\n"): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage($glue);
    }

    // --- Query methods ---

    public function statusMessage(): string
    {
        if ($this->hasError()) {
            return $this->errorMessage();
        }

        if ($this->hasSuccess()) {
            return $this->successMessage();
        }
        return '';
    }

    /**
     * @return bool
     */
    public function hasInvoiceLockedError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_LOCKED]);
    }

    public function hasBidderAbsentEmailError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_BIDDER_EMAIL_ABSENT]);
    }

    public function hasBidderUserNotFoundError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_BIDDER_USER_NOT_FOUND]);
    }

    public function hasInactiveUserError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_NOT_ACTIVE_USER]);
    }

    public function logData(): void
    {
        $prefix = 'INVOICE NOTIFY; ';
        $logMessage = '';
        if ($this->hasError()) {
            $logData = [
                'i' => $this->invoice->Id,
                'invoice#' => $this->invoice->InvoiceNo,
                'status' => $this->invoice->InvoiceStatusId,
                'active for action' => 'false'
            ];
            $logMessage = $prefix . "Invoice email notification cannot be sent";
            $logMessage .= $this->hasInactiveUserError() ? ' because bidder user is deleted' : '';
            $logMessage .= composeLogData($logData);
        }

        if ($this->hasSuccess()) {
            $logData = [
                'i' => $this->invoice->Id,
                'invoice#' => $this->invoice->InvoiceNo,
                'to inv. u' => $this->invoice->BidderId,
                'to inv. username' => $this->invoiceUser->Username,
                'by u' => $this->editorUserId,
            ];
            $logMessage = $prefix . "Sending email notification about invoice" . composeLogData($logData);
        }
        log_info($logMessage);
    }
}
