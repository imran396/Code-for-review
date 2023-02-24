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

namespace Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle;


use Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Build\RedirectUrlBuilderCreateTrait;
use Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Produce\InvoiceProducerCreateTrait;
use Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Build\MessageBuilderCreateTrait;
use Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Produce\PaymentProducerCreateTrait;
use Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\PublicInvoiceViewSuccessCallbackResponseHandlingInput as Input;
use Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\PublicInvoiceViewSuccessCallbackResponseHandleResult as Result;
use Sam\Core\Service\CustomizableClass;

class PublicInvoiceViewSuccessCallbackResponseHandler extends CustomizableClass
{
    use InvoiceProducerCreateTrait;
    use PaymentProducerCreateTrait;
    use MessageBuilderCreateTrait;
    use RedirectUrlBuilderCreateTrait;

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
            $input->invoiceId,
            $input->userId,
            $input->ccType,
            $input->amount,
            $input->threeDStatusResponse,
            $input->transactionId,
            $input->dateTime,
            $input->editorUserId,
            $input->isReadOnlyDb
        );

        $this->createInvoiceProducer()->produce(
            $input->invoiceId,
            $input->editorUserId,
            $input->isReadOnlyDb
        );

        $successMessage = $this->createMessageBuilder()->build(
            $input->accountId,
            $input->userId,
            $input->systemAccountId,
            $input->languageId,
            $input->isReadOnlyDb
        );

        $redirectUrl = $this->createRedirectUrlBuilder()->build(
            $input->url,
            $input->userId,
            $input->isReadOnlyDb
        );

        return Result::new()->construct($redirectUrl)
            ->addSuccess(Result::OK_SUCCESS, $successMessage);
    }
}
