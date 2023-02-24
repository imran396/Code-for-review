<?php
/**
 *
 * SAM-4558: Settlement Deleter module
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-02
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Load\SettlementItemLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\SettlementItem\SettlementItemWriteRepositoryAwareTrait;
use SettlementItem;

/**
 * Class SettlementItemDeleter
 * @package Sam\Settlement\Delete
 */
class SettlementItemDeleter extends CustomizableClass
{
    use SettlementItemLoaderAwareTrait;
    use SettlementItemWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete Settlement Item
     * @param SettlementItem $settlementItem
     * @param int $editorUserId
     */
    public function delete(SettlementItem $settlementItem, int $editorUserId): void
    {
        $settlementItem->Active = false;
        $this->getSettlementItemWriteRepository()->saveWithModifier($settlementItem, $editorUserId);
    }

    /**
     * Delete SettlementItem By ID
     * @param int $settlementItemId
     * @param int $editorUserId
     */
    public function deleteById(int $settlementItemId, int $editorUserId): void
    {
        $settlementItem = $this->getSettlementItemLoader()->load($settlementItemId, true);
        if ($settlementItem) {
            $this->delete($settlementItem, $editorUserId);
        }
    }
}
