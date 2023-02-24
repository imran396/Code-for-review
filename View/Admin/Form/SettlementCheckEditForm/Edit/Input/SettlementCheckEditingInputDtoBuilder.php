<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           11-14, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementCheckEditForm\Edit\Input;

use Laminas\Diactoros\ServerRequest;
use Sam\Core\Constants\Admin\SettlementCheckEditFormConstants;
use Sam\Core\Dto\FormDataReaderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\Edit\Single\Common\Input\SettlementCheckEditingInput;

/**
 * Class SettlementCheckEditingInputDtoBuilder
 * @package Sam\Settlement\Check\Action\Edit\Single\Common\Input\Build
 */
class SettlementCheckEditingInputDtoBuilder extends CustomizableClass
{
    use FormDataReaderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function fromPsrRequest(
        ServerRequest $psrRequest,
        int $systemAccountId,
        int $editorUserId,
        ?int $settlementCheckId,
        ?int $settlementId,
        bool $isDropPrintedOn,
        bool $isDropVoidedOn
    ): SettlementCheckEditingInput {
        $body = $psrRequest->getParsedBody();
        $formDataReader = $this->createFormDataReader();

        $checkNo = $formDataReader->readString(SettlementCheckEditFormConstants::CID_TXT_CHECK_NO, $body);
        $payee = $formDataReader->readString(SettlementCheckEditFormConstants::CID_TXT_PAYEE, $body);
        $amount = $formDataReader->readString(SettlementCheckEditFormConstants::CID_TXT_AMOUNT, $body);
        $amountSpelling = $formDataReader->readString(SettlementCheckEditFormConstants::CID_TXT_AMOUNT_SPELLING, $body);
        $memo = $formDataReader->readString(SettlementCheckEditFormConstants::CID_TXT_MEMO, $body);
        $note = $formDataReader->readString(SettlementCheckEditFormConstants::CID_TXT_NOTE, $body);
        $address = $formDataReader->readString(SettlementCheckEditFormConstants::CID_TXT_ADDRESS, $body);
        $postedOn = $formDataReader->readString(SettlementCheckEditFormConstants::CID_CAL_POSTED_DATE, $body);
        $clearedOn = $formDataReader->readString(SettlementCheckEditFormConstants::CID_CAL_CLEARED_DATE, $body);

        $dto = SettlementCheckEditingInput::new()->construct(
            $systemAccountId,
            $editorUserId,
            $settlementCheckId,
            $settlementId,
            $checkNo,
            $payee,
            $amount,
            $amountSpelling,
            $memo,
            $note,
            $address,
            $postedOn,
            $clearedOn,
            $isDropPrintedOn,
            $isDropVoidedOn
        );

        return $dto;
    }
}
