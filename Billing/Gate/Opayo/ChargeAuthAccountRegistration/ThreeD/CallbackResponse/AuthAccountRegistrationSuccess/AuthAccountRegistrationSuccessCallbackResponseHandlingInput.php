<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Oct 3, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAccountRegistration\ThreeD\CallbackResponse\AuthAccountRegistrationSuccess;

use Sam\Core\Service\CustomizableClass;

/**
 * Class AuthAccountRegistrationSuccessCallbackResponseHandlingInput
 * @package Sam\Billing\Gate\Opayo\ChargeAuthAccountRegistration\ThreeD\CallbackResponse\AuthAccountRegistrationSuccess
 */
class AuthAccountRegistrationSuccessCallbackResponseHandlingInput extends CustomizableClass
{
    public readonly int $accountId;
    public readonly string $firstName;
    public readonly string $lastName;
    public readonly ?int $opayoAuthTransactionType;
    public readonly string $transactionId;
    public readonly string $threeDStatusResponse;
    public readonly string $securityKey;
    public readonly float $amount;
    public readonly string $responseText;
    public readonly ?string $vtx;
    public readonly int $systemAccountId;
    public readonly ?int $languageId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $accountId
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string $responseText
     * @param string $transactionId
     * @param string $threeDStatusResponse
     * @param string $securityKey
     * @param float $amount
     * @param string|null $vtx
     * @param int|null $opayoAuthTransactionType
     * @param int $systemAccountId
     * @param int|null $languageId
     * @return $this
     */
    public function construct(
        int $accountId,
        ?string $firstName,
        ?string $lastName,
        string $responseText,
        string $transactionId,
        string $threeDStatusResponse,
        string $securityKey,
        float $amount,
        ?string $vtx,
        ?int $opayoAuthTransactionType,
        int $systemAccountId,
        ?int $languageId
    ): static {
        $this->accountId = $accountId;
        $this->firstName = $firstName ?? '';
        $this->lastName = $lastName ?? '';
        $this->responseText = $responseText;
        $this->transactionId = $transactionId;
        $this->threeDStatusResponse = $threeDStatusResponse;
        $this->securityKey = $securityKey;
        $this->amount = $amount;
        $this->vtx = $vtx;
        $this->opayoAuthTransactionType = $opayoAuthTransactionType;
        $this->systemAccountId = $systemAccountId;
        $this->languageId = $languageId;
        return $this;
    }
}
