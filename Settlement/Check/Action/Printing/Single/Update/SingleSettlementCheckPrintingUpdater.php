<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\Printing\Single\Update;

use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Settlement\Check\Action\Printing\Exception\CouldNotPrintCheck;
use Sam\Settlement\Check\Load\Exception\CouldNotFindSettlementCheck;
use Sam\Settlement\Check\Load\SettlementCheckLoaderCreateTrait;
use Sam\Storage\WriteRepository\Entity\SettlementCheck\SettlementCheckWriteRepositoryAwareTrait;
use SettlementCheck;

/**
 * Class SingleSettlementCheckPrintingUpdater
 * @package Sam\Settlement\Check
 */
class SingleSettlementCheckPrintingUpdater extends CustomizableClass
{
    use CurrentDateTrait;
    use SettlementCheckLoaderCreateTrait;
    use SettlementCheckWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function markPrinted(int $settlementCheckId, int $editorUserId, bool $isReadOnlyDb = false): SettlementCheck
    {
        $settlementCheck = $this->createSettlementCheckLoader()->load($settlementCheckId, $isReadOnlyDb);
        if (!$settlementCheck) {
            throw CouldNotFindSettlementCheck::withId($settlementCheckId);
        }

        return $this->markPrintedCheck($settlementCheck, $editorUserId);
    }

    public function markPrintedCheck(SettlementCheck $settlementCheck, int $editorUserId): SettlementCheck
    {
        if ($settlementCheck->isPrinted()) {
            throw CouldNotPrintCheck::becauseAlreadyPrinted($settlementCheck->Id);
        }

        $settlementCheck->PrintedOn = $this->getCurrentDateUtc();
        $this->getSettlementCheckWriteRepository()->saveWithModifier($settlementCheck, $editorUserId);
        return $settlementCheck;
    }

}
