<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;


class PublicInvoiceViewSuccessCallbackResponseHandleResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_FAILED_TO_HANDLE_CALLBACK = 1;

    public const OK_SUCCESS = 11;

    public readonly string $redirectUrl;


    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $redirectUrl): static
    {
        $this->getResultStatusCollector()->construct(
            [
                self::ERR_FAILED_TO_HANDLE_CALLBACK => 'Error updating customer Opayo token',
            ],
            [self::OK_SUCCESS => 'Successfully updated user info and user billing']
        );
        $this->redirectUrl = $redirectUrl;
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
