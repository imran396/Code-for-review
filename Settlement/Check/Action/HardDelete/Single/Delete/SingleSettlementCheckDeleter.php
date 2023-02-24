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

namespace Sam\Settlement\Check\Action\HardDelete\Single\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\HardDelete\Single\Delete\SingleSettlementCheckDeletionResult as Result;
use Sam\Settlement\Check\Load\Exception\CouldNotFindSettlementCheck;
use Sam\Settlement\Check\Load\SettlementCheckLoaderCreateTrait;
use Sam\Storage\WriteRepository\Entity\SettlementCheck\SettlementCheckWriteRepositoryAwareTrait;
use SettlementCheck;

/**
 * Class SingleSettlementCheckDeleter
 * @package Sam\Settlement\Check
 */
class SingleSettlementCheckDeleter extends CustomizableClass
{
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

    /**
     * @param int $settlementCheckId
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return Result
     */
    public function delete(int $settlementCheckId, int $editorUserId, bool $isReadOnlyDb = false): Result
    {
        $settlementCheck = $this->createSettlementCheckLoader()->load($settlementCheckId, $isReadOnlyDb);
        if (!$settlementCheck) {
            throw CouldNotFindSettlementCheck::withId($settlementCheckId);
        }
        $this->deleteCheck($settlementCheck, $editorUserId);

        return Result::new()->construct($settlementCheck, true);
    }

    public function deleteCheck(SettlementCheck $settlementCheck, int $editorUserId): void
    {
        $this->getSettlementCheckWriteRepository()->deleteWithModifier($settlementCheck, $editorUserId);
    }
}
