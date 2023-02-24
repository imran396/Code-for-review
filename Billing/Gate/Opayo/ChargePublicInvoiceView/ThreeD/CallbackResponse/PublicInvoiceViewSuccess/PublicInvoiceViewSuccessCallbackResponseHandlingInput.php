<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Sept 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess;

use DateTime;
use Sam\Core\Service\CustomizableClass;


class PublicInvoiceViewSuccessCallbackResponseHandlingInput extends CustomizableClass
{
    public readonly ?int $accountId;
    public readonly ?int $mainAccountId;
    public readonly ?int $userId;
    public readonly ?int $invoiceId;
    public readonly int $ccType;
    public readonly float $amount;
    public readonly string $transactionId;
    public readonly string $threeDStatusResponse;
    public readonly array $billingParams;
    public readonly DateTime $dateTime;
    public readonly string $url;
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
     * @param int|null $accountId
     * @param int|null $mainAccountId
     * @param int|null $userId
     * @param int|null $invoiceId
     * @param int $ccType
     * @param float $amount
     * @param string $transactionId
     * @param string $threeDStatusResponse
     * @param array $billingParams
     * @param DateTime $dateTime
     * @param string $url
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param int|null $languageId
     * @param bool $isReadOnlyDb
     * @return $this
     */
    public function construct(
        ?int $accountId,
        ?int $mainAccountId,
        ?int $userId,
        ?int $invoiceId,
        int $ccType,
        float $amount,
        string $transactionId,
        string $threeDStatusResponse,
        array $billingParams,
        DateTime $dateTime,
        string $url,
        ?int $editorUserId,
        int $systemAccountId,
        ?int $languageId,
        bool $isReadOnlyDb,
    ): static {
        $this->accountId = $accountId;
        $this->mainAccountId = $mainAccountId;
        $this->userId = $userId;
        $this->invoiceId = $invoiceId;
        $this->ccType = $ccType;
        $this->amount = $amount;
        $this->transactionId = $transactionId;
        $this->threeDStatusResponse = $threeDStatusResponse;
        $this->dateTime = $dateTime;
        $this->url = $url;
        $this->billingParams = $billingParams;
        $this->editorUserId = $editorUserId;
        $this->systemAccountId = $systemAccountId;
        $this->languageId = $languageId;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }
}
