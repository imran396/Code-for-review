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

namespace Sam\Settlement\Check\Action\MarkCleared\Single\Update;

use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Settlement\Check\Load\Exception\CouldNotFindSettlementCheck;
use Sam\Settlement\Check\Load\SettlementCheckLoaderCreateTrait;
use Sam\Settlement\Check\Action\MarkCleared\Single\Update\Exception\CouldNotMarkClearedCheck;
use Sam\Storage\WriteRepository\Entity\SettlementCheck\SettlementCheckWriteRepositoryAwareTrait;
use SettlementCheck;

/**
 * Class SingleSettlementCheckClearedMarker
 * @package Sam\Settlement\Check
 */
class SingleSettlementCheckClearedMarker extends CustomizableClass
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

    public function markCleared(int $settlementCheckId, int $editorUserId, bool $isReadOnlyDb = false): SettlementCheck
    {
        $settlementCheck = $this->createSettlementCheckLoader()->load($settlementCheckId, $isReadOnlyDb);
        if (!$settlementCheck) {
            throw CouldNotFindSettlementCheck::withId($settlementCheckId);
        }

        return $this->markClearedCheck($settlementCheck, $editorUserId);
    }

    public function markClearedCheck(SettlementCheck $settlementCheck, int $editorUserId): SettlementCheck
    {
        if ($settlementCheck->isCleared()) {
            throw CouldNotMarkClearedCheck::becauseAlreadyCleared($settlementCheck->Id);
        }

        $settlementCheck->ClearedOn = $this->getCurrentDateUtc();
        $this->getSettlementCheckWriteRepository()->saveWithModifier($settlementCheck, $editorUserId);
        return $settlementCheck;
    }

}
