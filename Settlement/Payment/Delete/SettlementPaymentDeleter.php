<?php
/**
 * SAM-9960: Check Printing for Settlements: Payment List management at the "Settlement Edit" page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Payment\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Load\SettlementCheckLoaderCreateTrait;
use Sam\Settlement\Payment\Load\SettlementPaymentLoaderCreateTrait;
use Sam\Storage\WriteRepository\Entity\Payment\PaymentWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\SettlementCheck\SettlementCheckWriteRepositoryAwareTrait;

/**
 * Class SettlementPaymentDeleter
 * @package Sam\Settlement\Payment\Delete
 */
class SettlementPaymentDeleter extends CustomizableClass
{
    use PaymentWriteRepositoryAwareTrait;
    use SettlementCheckLoaderCreateTrait;
    use SettlementCheckWriteRepositoryAwareTrait;
    use SettlementPaymentLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function deleteAbsent(int $settlementId, array $existingPaymentIds, int $editorUserId): void
    {
        $absentPayments = $this->createSettlementPaymentLoader()->loadBySettlementId($settlementId, $existingPaymentIds);
        foreach ($absentPayments as $absentPayment) {
            $this->dropSettlementCheckPaymentId($absentPayment->Id, $editorUserId);
            $absentPayment->toDeleted();
            $this->getPaymentWriteRepository()->saveWithModifier($absentPayment, $editorUserId);
        }
    }

    protected function dropSettlementCheckPaymentId(int $paymentId, int $editorUserId): void
    {
        $checks = $this->createSettlementCheckLoader()->loadByPaymentId($paymentId);
        foreach ($checks as $check) {
            $check->PaymentId = null;
            $this->getSettlementCheckWriteRepository()->saveWithModifier($check, $editorUserId);
        }
    }
}
