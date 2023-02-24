<?php
/**
 * SAM-11778: Refactor Invoice Notifier for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 24, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Notify\Multiple;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;

/**
 * Class MultipleInvoiceNotificationResult
 * @package Sam\Invoice\Common\Notify\Multiple
 */
class MultipleInvoiceNotificationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_WITH_LOCKED_INVOICES = 1;
    public const ERR_NO_INVOICE_SPECIFIED = 2;
    public const ERR_BIDDER_EMAIL_ABSENT = 3;
    public const ERR_BIDDER_USER_NOT_FOUND = 4;
    public const ERR_NO_ACTIVATE_USER = 5;

    public const OK_NOTIFIED = 11;

    protected const SUCCESS_MESSAGES = [
        self::OK_NOTIFIED => 'notified.success.message',
    ];

    protected const ERROR_MESSAGES = [
        self::ERR_WITH_LOCKED_INVOICES => 'Locked invoices #: %s.',
        self::ERR_NO_INVOICE_SPECIFIED => 'No invoice has been selected to send email.',
        self::ERR_BIDDER_EMAIL_ABSENT => '%s invoice email(s) unsent, bidder e-mail absent for invoice #  %s and username %s respectively.',
        self::ERR_BIDDER_USER_NOT_FOUND => '%s invoice email(s) unsent, Wrong bidder for invoice # %s and bidder id # %s respectively.',
        self::ERR_NO_ACTIVATE_USER => '%s invoice email(s) unsent, Inactive bidder user found for invoice #:  %s.'
    ];

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

    public function logData(): array
    {
        $logData = [];
        if ($this->hasError()) {
            $logData += [
                'error code' => $this->errorCodes(),
                'error message' => $this->errorMessage()
            ];
        }
        if ($this->hasSuccess()) {
            $logData += [
                'success code' => $this->successCodes(),
                'success message' => $this->successMessage()
            ];
        }
        return $logData;
    }
}
