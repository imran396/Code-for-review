<?php
/**
 * SAM-9890: Check Printing for Settlements: Implementation of printing content rendering
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\Printing\Multiple\Update;

use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Settlement\Check\Action\Printing\Exception\CouldNotPrintCheck;
use Sam\Settlement\Check\Load\SettlementCheckLoaderCreateTrait;
use Sam\Storage\WriteRepository\Entity\SettlementCheck\SettlementCheckWriteRepositoryAwareTrait;
use SettlementCheck;

/**
 * Class MultipleSettlementCheckPrintingUpdater
 * @package Sam\Settlement\Check
 */
class MultipleSettlementCheckPrintingUpdater extends CustomizableClass
{
    use CurrentDateTrait;
    use SettlementCheckLoaderCreateTrait;
    use SettlementCheckWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $settlementCheckIds
     * @param int $startingCheckNo
     * @param int $editorUserId
     * @return SettlementCheck[]
     */
    public function fillCheckNoAndMarkPrinted(array $settlementCheckIds, int $startingCheckNo, int $editorUserId): array
    {
        $settlementChecks = $this->createSettlementCheckLoader()->yieldByIds($settlementCheckIds);
        $resultSettlementChecks = [];
        foreach ($settlementChecks as $settlementCheck) {
            $resultSettlementChecks[] = $this->fillCheckNoAndMarkPrintedCheck($settlementCheck, $startingCheckNo++, $editorUserId);
        }
        return $resultSettlementChecks;
    }

    protected function fillCheckNoAndMarkPrintedCheck(SettlementCheck $settlementCheck, int $checkNo, int $editorUserId): SettlementCheck
    {
        if ($settlementCheck->isPrinted()) {
            throw CouldNotPrintCheck::becauseAlreadyPrinted($settlementCheck->Id);
        }
        if ($settlementCheck->hasCheckNo()) {
            throw CouldNotPrintCheck::becauseCheckNoIsFilled($settlementCheck->Id);
        }
        $settlementCheck->CheckNo = $checkNo;
        $settlementCheck->PrintedOn = $this->getCurrentDateUtc();
        $this->getSettlementCheckWriteRepository()->saveWithModifier($settlementCheck, $editorUserId);
        return $settlementCheck;
    }
}
