<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Jun 1, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementSuccess\Handle;


use Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementSuccess\Handle\Internal\Build\NoteBuilderCreateTrait;
use Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementSuccess\Handle\Internal\Load\DataProviderCreateTrait;
use Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementSuccess\Handle\Internal\Notify\NotifierCreateTrait;
use Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementSuccess\Handle\Internal\Payment\PaymentProducerCreateTrait;
use Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementSuccess\Handle\SettlementSuccessCallbackResponseHandleResult as Result;
use Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementSuccess\SettlementSuccessCallbackResponseHandlingInput as Input;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Load\Exception\CouldNotFindSettlement;
use Sam\Storage\WriteRepository\Entity\Settlement\SettlementWriteRepositoryAwareTrait;
use Settlement;


class SettlementSuccessCallbackResponseHandler extends CustomizableClass
{
    use PaymentProducerCreateTrait;
    use NoteBuilderCreateTrait;
    use NotifierCreateTrait;
    use DataProviderCreateTrait;
    use SettlementWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function handle(Input $input): Result
    {
        $this->createPaymentProducer()->produce(
            $input->settlementId,
            $input->userId,
            $input->amount,
            $input->transactionId
        );

        $settlement = $this->applyOnSettlement($input);
        $successMessage = $this->composeSuccessMessage($settlement->SettlementNo);
        $result = Result::new()->construct($settlement)
            ->addSuccess(Result::OK_SUCCESS_PAYMENT, $successMessage);
        return $result;
    }

    protected function composeSuccessMessage(?int $settlementNo): string
    {
        $paymentSuccess = sprintf(
            'Successfully charge commission on settlement %s through Opayo! <br />',
            $settlementNo
        );
        return $paymentSuccess;
    }


    protected function applyOnSettlement(Input $input): Settlement
    {
        $dataProvider = $this->createDataProvider();

        $settlement = $dataProvider->loadSettlement($input->settlementId);
        if (!$settlement) {
            throw CouldNotFindSettlement::withId($input->settlementId);
        }

        //change settlements status to paid
        $settlement->toPaid();
        $settlement->Note = $this->createNoteBuilder()->build(
            $input->settlementId,
            $settlement->Note,
            $input->threeDStatusResponse,
            $dataProvider->detectDefaultSign(),
            $input->amount,
            $input->systemAccountId,
            $input->languageId
        );
        $this->getSettlementWriteRepository()->saveWithModifier($settlement, $input->editorUserId);

        log_info(
            'Sending payment confirmation for settlement and processing settlement'
            . composeSuffix(['s' => $settlement->Id, 'settlement#' => $settlement->SettlementNo])
        );
        $this->createNotifier()->addEmailToActionQueue($settlement, $input->editorUserId);

        return $settlement;
    }

}
