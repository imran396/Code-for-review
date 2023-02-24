<?php
/**
 * SAM-9888: Check Printing for Settlements: Bulk Checks Processing - Account level, Settlements List level (Part 2)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementCheckCreateBatchForm\Input;

use Laminas\Diactoros\ServerRequest;
use Sam\Core\Constants\Admin\SettlementCheckCreateBatchFormConstants;
use Sam\Core\Dto\FormDataReaderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\Edit\Single\Common\Input\SettlementCheckEditingInput;

/**
 * Class SettlementCheckEditingInputFactory
 * @package Sam\View\Admin\Form\SettlementCheckCreateBatchForm\Input
 */
class SettlementCheckEditingInputFactory extends CustomizableClass
{
    use FormDataReaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function fromPsrRequest(
        ServerRequest $psrRequest,
        int $systemAccountId,
        int $editorUserId,
        int $settlementId
    ): ?SettlementCheckEditingInput {
        $body = $psrRequest->getParsedBody();
        $formDataReader = $this->createFormDataReader();

        $checkNo = $formDataReader->readString(sprintf(SettlementCheckCreateBatchFormConstants::CID_TXT_CHECK_NO_TPL, $settlementId), $body);
        if ($checkNo === null) {
            return null;
        }
        $payee = $formDataReader->readString(sprintf(SettlementCheckCreateBatchFormConstants::CID_TXT_PAYEE_TPL, $settlementId), $body);
        $amount = $formDataReader->readString(sprintf(SettlementCheckCreateBatchFormConstants::CID_TXT_AMOUNT_TPL, $settlementId), $body);
        $amountSpelling = $formDataReader->readString(sprintf(SettlementCheckCreateBatchFormConstants::CID_TXT_AMOUNT_SPELLING_TPL, $settlementId), $body);
        $memo = $formDataReader->readString(sprintf(SettlementCheckCreateBatchFormConstants::CID_TXT_MEMO_TPL, $settlementId), $body);
        $note = $formDataReader->readString(sprintf(SettlementCheckCreateBatchFormConstants::CID_TXT_NOTE_TPL, $settlementId), $body);
        $address = $formDataReader->readString(sprintf(SettlementCheckCreateBatchFormConstants::CID_TXT_ADDRESS_TPL, $settlementId), $body);

        $dto = SettlementCheckEditingInput::new()->construct(
            $systemAccountId,
            $editorUserId,
            null,
            $settlementId,
            $checkNo,
            $payee,
            $amount,
            $amountSpelling,
            $memo,
            $note,
            $address,
            null,
            null,
            false,
            false
        );

        return $dto;
    }
}
