<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Oct 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementSuccess;

use Sam\Core\Service\CustomizableClass;


class SettlementSuccessCallbackResponseHandlingInput extends CustomizableClass
{
    public readonly ?int $userId;
    public readonly ?int $settlementId;
    public readonly float $amount;
    public readonly string $transactionId;
    public readonly string $threeDStatusResponse;
    public readonly ?int $editorUserId;
    public readonly int $systemAccountId;
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
     * @param int|null $userId
     * @param int|null $settlementId
     * @param float $amount
     * @param string $transactionId
     * @param string $threeDStatusResponse
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param int|null $languageId
     * @param bool $isReadOnlyDb
     * @return $this
     */
    public function construct(
        ?int $userId,
        ?int $settlementId,
        float $amount,
        string $transactionId,
        string $threeDStatusResponse,
        ?int $editorUserId,
        int $systemAccountId,
        ?int $languageId,
        bool $isReadOnlyDb
    ): static {
        $this->userId = $userId;
        $this->settlementId = $settlementId;
        $this->amount = $amount;
        $this->transactionId = $transactionId;
        $this->threeDStatusResponse = $threeDStatusResponse;
        $this->editorUserId = $editorUserId;
        $this->systemAccountId = $systemAccountId;
        $this->languageId = $languageId;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }
}
