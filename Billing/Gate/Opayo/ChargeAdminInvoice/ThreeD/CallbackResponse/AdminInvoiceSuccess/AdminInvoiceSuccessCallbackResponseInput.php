<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Sept 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceSuccess;

use DateTime;
use Sam\Core\Service\CustomizableClass;


class AdminInvoiceSuccessCallbackResponseInput extends CustomizableClass
{
    public ?int $accountId;
    public ?int $userId;
    public ?int $invoiceId;
    public string $ccNumber;
    public int $creditCardId;
    public float $amount;
    public string $transactionId;
    public string $threeDStatusResponse;
    public string $paymentType;
    public DateTime $currentDate;
    public int $editorUserId;
    public bool $isReadOnlyDb;

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
     * @param int|null $userId
     * @param int|null $invoiceId
     * @param string $ccNumber
     * @param int $creditCardId
     * @param float $amount
     * @param string $transactionId
     * @param string $threeDStatusResponse
     * @param string $paymentType
     * @param DateTime $currentDate
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return $this
     */
    public function construct(
        ?int $accountId,
        ?int $userId,
        ?int $invoiceId,
        string $ccNumber,
        int $creditCardId,
        float $amount,
        string $transactionId,
        string $threeDStatusResponse,
        string $paymentType,
        DateTime $currentDate,
        int $editorUserId,
        bool $isReadOnlyDb,
    ): static {
        $this->accountId = $accountId;
        $this->userId = $userId;
        $this->invoiceId = $invoiceId;
        $this->ccNumber = $ccNumber;
        $this->creditCardId = $creditCardId;
        $this->amount = $amount;
        $this->transactionId = $transactionId;
        $this->threeDStatusResponse = $threeDStatusResponse;
        $this->paymentType = $paymentType;
        $this->currentDate = $currentDate;
        $this->editorUserId = $editorUserId;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }
}
