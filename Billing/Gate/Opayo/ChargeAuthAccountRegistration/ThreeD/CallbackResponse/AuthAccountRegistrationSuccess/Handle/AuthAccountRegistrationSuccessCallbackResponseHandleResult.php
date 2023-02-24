<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAccountRegistration\ThreeD\CallbackResponse\AuthAccountRegistrationSuccess\Handle;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;


class AuthAccountRegistrationSuccessCallbackResponseHandleResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const OK_SUCCESS = 1;

    public const ERR_TRANSACTION_ERROR = 11;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(
            [self::ERR_TRANSACTION_ERROR => 'Transaction has been declined'],
            [self::OK_SUCCESS => 'Successfully handled auction registration callback']
        );
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

    // --- Query ---
    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasTransactionError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_TRANSACTION_ERROR);
    }

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
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
}
