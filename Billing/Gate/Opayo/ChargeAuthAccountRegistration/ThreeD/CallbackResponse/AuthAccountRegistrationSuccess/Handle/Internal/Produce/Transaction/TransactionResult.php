<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAccountRegistration\ThreeD\CallbackResponse\AuthAccountRegistrationSuccess\Handle\Internal\Produce\Transaction;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;


class TransactionResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    private string $responseCode;
    private string $cardCodeResponse;
    private string $responseText;

    public const ERR_TRANSACTION_HAS_BEEN_DECLINED = 1;
    public const OK_SUCCESS_TRANSACTION = 11;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $responseCode,
        string $cardCodeResponse,
        string $responseText
    ): static {
        $this->getResultStatusCollector()->construct(
            [self::ERR_TRANSACTION_HAS_BEEN_DECLINED => 'Transaction has been declined'],
            [self::OK_SUCCESS_TRANSACTION => 'Transaction has been successfully processed']
        );
        $this->responseCode = $responseCode;
        $this->cardCodeResponse = $cardCodeResponse;
        $this->responseText = $responseText;

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

    // --- Query ---
    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    public function errorMessage(string $glue = null): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
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

    public function getResponseCode(): string
    {
        return $this->responseCode;
    }

    public function getCardCodeResponse(): string
    {
        return $this->cardCodeResponse;
    }

    public function getResponseText(): string
    {
        return $this->responseText;
    }
}
