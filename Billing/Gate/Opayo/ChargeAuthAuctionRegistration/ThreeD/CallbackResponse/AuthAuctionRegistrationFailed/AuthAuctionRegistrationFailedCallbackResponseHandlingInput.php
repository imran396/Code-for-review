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

namespace Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationFailed;

use Sam\Core\Service\CustomizableClass;


class AuthAuctionRegistrationFailedCallbackResponseHandlingInput extends CustomizableClass
{
    public readonly ?int $auctionId;
    public readonly ?int $userId;
    public readonly float $amount;
    public readonly string $responseText;
    public readonly string $threeDSecureErrorMessage;
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
     * @param int|null $auctionId
     * @param int|null $userId
     * @param float $amount
     * @param string $responseText
     * @param string $threeDSecureErrorMessage
     * @param string $url
     * @param string $carrierMethod
     * @param int $systemAccountId
     * @param int $editorUserId
     * @param int|null $languageId
     * @param bool $isReadOnlyDb
     * @return $this
     */
    public function construct(
        ?int $auctionId,
        ?int $userId,
        float $amount,
        string $responseText,
        string $threeDSecureErrorMessage,
        string $url,
        string $carrierMethod,
        int $systemAccountId,
        int $editorUserId,
        ?int $languageId,
        bool $isReadOnlyDb,
    ): static {
        $this->auctionId = $auctionId;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->responseText = $responseText;
        $this->threeDSecureErrorMessage = $threeDSecureErrorMessage;
        $this->editorUserId = $editorUserId;
        $this->url = $url;
        $this->carrierMethod = $carrierMethod;
        $this->systemAccountId = $systemAccountId;
        $this->languageId = $languageId;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }
}
