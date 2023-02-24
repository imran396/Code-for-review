<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\Edit\Single\Update;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Create\EntityFactory;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Settlement\Check\Action\Edit\Single\Common\Input\SettlementCheckEditingInput as Input;
use Sam\Settlement\Check\Action\Edit\Single\Update\Internal\Load\DataProviderCreateTrait;
use Sam\Settlement\Check\Action\Edit\Single\Update\Internal\Result\SettlementCheckEditingSavingResult;
use Sam\Settlement\Check\Load\Exception\CouldNotFindSettlementCheck;
use Sam\Storage\WriteRepository\Entity\SettlementCheck\SettlementCheckWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class SettlementCheckEditingSaver
 * @package Sam\Settlement\Check
 */
class SettlementCheckEditingSaver extends CustomizableClass
{
    use DataProviderCreateTrait;
    use DateHelperAwareTrait;
    use NumberFormatterAwareTrait;
    use SettlementCheckWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function save(Input $input, bool $isReadOnlyDb = false): SettlementCheckEditingSavingResult
    {
        $numberFormatter = $this->getNumberFormatter()->construct($input->systemAccountId);
        $dateHelper = $this->getDateHelper();

        if ($input->settlementCheckId) {
            $settlementCheck = $this->createDataProvider()->loadSettlementCheck($input->settlementCheckId, $isReadOnlyDb);
            if (!$settlementCheck) {
                throw CouldNotFindSettlementCheck::withId($input->settlementCheckId);
            }
        } else {
            $settlementCheck = EntityFactory::new()->settlementCheck();
            $settlementCheck->SettlementId = $input->settlementId;
        }

        $isNew = !$settlementCheck->__Restored;

        if (isset($input->checkNo)) {
            $settlementCheck->CheckNo = Cast::toInt($input->checkNo);
        }

        if (isset($input->payee)) {
            $settlementCheck->Payee = $input->payee;
        }

        if (isset($input->amount)) {
            $settlementCheck->Amount = $numberFormatter->parseMoney($input->amount);
        }

        if (isset($input->amountSpelling)) {
            $settlementCheck->AmountSpelling = $input->amountSpelling;
        }

        if (isset($input->memo)) {
            $settlementCheck->Memo = $input->memo;
        }

        if (isset($input->note)) {
            $settlementCheck->Note = $input->note;
        }

        if (isset($input->address)) {
            $settlementCheck->Address = $input->address;
        }

        if (isset($input->postedOnSysIso)) {
            $postedOn = $input->postedOnSysIso
                ? $dateHelper->convertSysToUtcByDateIso($input->postedOnSysIso, $input->systemAccountId)
                : null;
            $settlementCheck->PostedOn = $postedOn;
        }

        if (isset($input->clearedOnSysIso)) {
            $clearedOn = $input->clearedOnSysIso
                ? $dateHelper->convertSysToUtcByDateIso($input->clearedOnSysIso, $input->systemAccountId)
                : null;
            $settlementCheck->ClearedOn = $clearedOn;
        }

        if ($input->isDropPrintedOn) {
            $settlementCheck->dropPrintedOn();
        }

        if ($input->isDropVoidedOn) {
            $settlementCheck->dropVoidedOn();
        }

        $this->getSettlementCheckWriteRepository()->saveWithModifier($settlementCheck, $input->editorUserId);
        $result = SettlementCheckEditingSavingResult::new()->construct($settlementCheck, $isNew);
        return $result;
    }
}
