<?php
/**
 *
 * SAM-4761: Lot from settlement removing service
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-13
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Settlement\SettlementReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\SettlementItem\SettlementItemReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\Settlement\SettlementWriteRepositoryAwareTrait;

/**
 * Class LotFromSettlementRemover
 * @package Sam\Settlement\Delete
 */
class LotFromSettlementRemover extends CustomizableClass
{
    use SettlementItemDeleterAwareTrait;
    use SettlementItemReadRepositoryCreateTrait;
    use SettlementReadRepositoryCreateTrait;
    use SettlementWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $lotItemId
     * @param int $editorUserId
     */
    public function remove(int $lotItemId, int $editorUserId): void
    {
        $settlementItems = $this->createSettlementItemReadRepository()
            ->filterLotItemId($lotItemId)
            ->filterActive(true)
            ->loadEntities();

        $settlementItemDeleter = $this->getSettlementItemDeleter();
        foreach ($settlementItems as $settlementItem) {
            $settlementItemDeleter->delete($settlementItem, $editorUserId);
        }
        $settlementRepository = $this->createSettlementReadRepository()
            ->joinSettlementItemFilterLotItemId($lotItemId)
            ->inlineCondition("(SELECT COUNT(1) FROM settlement_item WHERE settlement_id = si.settlement_id AND active = true) = 0");
        $settlements = $settlementRepository->loadEntities();
        foreach ($settlements as $settlement) {
            $settlement->toDeleted();
            $this->getSettlementWriteRepository()->saveWithModifier($settlement, $editorUserId);
        }
    }
}
