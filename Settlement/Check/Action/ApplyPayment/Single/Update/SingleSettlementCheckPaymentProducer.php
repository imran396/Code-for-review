<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\ApplyPayment\Single\Update;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\ApplyPayment\Single\Update\Exception\CouldNotApplySettlementPaymentByCheck;
use Sam\Settlement\Check\Action\ApplyPayment\Single\Update\Internal\Load\DataProviderCreateTrait;
use Sam\Settlement\Check\Load\Exception\CouldNotFindSettlementCheck;
use Sam\Settlement\Payment\Update\SettlementPayment;
use Sam\Settlement\Payment\Update\SettlementPaymentProducerCreateTrait;
use Sam\Storage\WriteRepository\Entity\SettlementCheck\SettlementCheckWriteRepositoryAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;
use SettlementCheck;
use Sam\Settlement\Check\Action\ApplyPayment\Single\Update\SingleSettlementCheckPaymentProductionResult as Result;

/**
 * Class SettlementCheckPaymentProducer
 * @package Sam\Settlement\Check
 */
class SingleSettlementCheckPaymentProducer extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use DataProviderCreateTrait;
    use SettlementCheckWriteRepositoryAwareTrait;
    use SettlementPaymentProducerCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function produce(int $settlementCheckId, int $editorUserId, bool $isReadOnlyDb = false): Result
    {
        $dataProvider = $this->createDataProvider();
        $settlementCheck = $dataProvider->loadSettlementCheck($settlementCheckId, $isReadOnlyDb);
        if (!$settlementCheck) {
            throw CouldNotFindSettlementCheck::withId($settlementCheckId);
        }

        return $this->produceByCheck($settlementCheck, $editorUserId);
    }

    public function produceByCheck(SettlementCheck $settlementCheck, int $editorUserId): Result
    {
        if ($settlementCheck->PaymentId) {
            throw CouldNotApplySettlementPaymentByCheck::becausePaymentExists($settlementCheck->Id);
        }

        $payment = $this->createSettlementPaymentProducer()->produce(
            SettlementPayment::new()->constructNew(
                $settlementCheck->SettlementId,
                Constants\Payment::PM_CHECK,
                $settlementCheck->Amount,
                $this->makeNote($settlementCheck)
            ),
            $editorUserId
        );
        $settlementCheck->PaymentId = $payment->Id;
        $this->getSettlementCheckWriteRepository()->saveWithModifier($settlementCheck, $editorUserId);
        return Result::new()->construct($settlementCheck, $payment);
    }

    protected function makeNote(SettlementCheck $settlementCheck): string
    {
        $printedOnFormatted = $settlementCheck->PrintedOn
            ? $settlementCheck->PrintedOn->format(Constants\Date::US_DATE)
            : '';
        return $this->getAdminTranslator()->trans(
            'check.payment.note_tpl',
            [
                'checkNo' => $settlementCheck->CheckNo,
                'payee' => $settlementCheck->Payee,
                'printedDate' => $printedOnFormatted,
            ],
            'admin_settlement'
        );
    }

}
