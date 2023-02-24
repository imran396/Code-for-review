<?php
/**
 * SAM-10915: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Authorize.Net invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 1, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\Internal\ProfileCcCharge;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\Internal\Common\ChargingResultInterface;

class ProfileCcChargingResult extends CustomizableClass implements ChargingResultInterface
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_CHARGE = 1;
    public const ERR_USER_NOT_FOUND = 2;

    public const OK_CHARGED = 11;
    public const OK_HELD_FOR_REVIEW = 12;

    protected const ERROR_MESSAGES = [
        self::ERR_CHARGE => 'CC charge failed',
        self::ERR_USER_NOT_FOUND => 'User not found',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_CHARGED => 'Updated successfully',
        self::OK_HELD_FOR_REVIEW => 'Held for review'
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

    public function addSuccess(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addSuccess($code, $message);
        return $this;
    }

    public function addError(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addError($code, $message);
        return $this;
    }

    public function setNoteInfo(array $noteInfo): static
    {
        $this->noteInfo = $noteInfo;
        return $this;
    }

    // --- Query error ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasTransactionStatusHeldForReview(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::OK_HELD_FOR_REVIEW);
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
