<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Oct 2, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess;

use Sam\Core\Service\CustomizableClass;


class AuthAuctionRegistrationSuccessCallbackResponseHandlingInput extends CustomizableClass
{
    public readonly int $accountId;
    public readonly ?int $auctionId;
    public readonly ?int $userId;
    public readonly int $opayoAuthTransactionType;
    public readonly ?string $vtx;
    public readonly float $amount;
    public readonly string $transactionId;
    public readonly string $threeDStatusResponse;
    public readonly string $securityKey;
    public readonly string $url;
    public readonly string $carrierMethod;
    public readonly int $systemAccountId;
    public readonly int $editorUserId;
    public readonly ?int $languageId;
    public readonly bool $isReadOnlyDb;

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
     * @param int|null $auctionId
     * @param int|null $userId
     * @param string|null $vtx
     * @param float $amount
     * @param string $transactionId
     * @param string $threeDStatusResponse
     * @param string $securityKey
     * @param int $opayoAuthTransactionType
     * @param string $url
     * @param string $carrierMethod
     * @param int $editorUserId
     * @param int $systemAccountId
     * @param int|null $languageId
     * @param bool $isReadOnlyDb
     * @return $this
     */
    public function construct(
        int $accountId,
        ?int $auctionId,
        ?int $userId,
        ?string $vtx,
        float $amount,
        string $transactionId,
        string $threeDStatusResponse,
        string $securityKey,
        int $opayoAuthTransactionType,
        string $url,
        string $carrierMethod,
        int $editorUserId,
        int $systemAccountId,
        ?int $languageId,
        bool $isReadOnlyDb
    ): static {
        $this->accountId = $accountId;
        $this->auctionId = $auctionId;
        $this->userId = $userId;
        $this->vtx = $vtx;
        $this->amount = $amount;
        $this->transactionId = $transactionId;
        $this->threeDStatusResponse = $threeDStatusResponse;
        $this->securityKey = $securityKey;
        $this->opayoAuthTransactionType = $opayoAuthTransactionType;
        $this->carrierMethod = $carrierMethod;
        $this->url = $url;
        $this->editorUserId = $editorUserId;
        $this->systemAccountId = $systemAccountId;
        $this->languageId = $languageId;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }
}
