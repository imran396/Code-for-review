<?php
/**
 * Lot Item Sync Data Loader
 *
 * SAM-6248: Refactor Lot Info Panel at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\LotItemSync\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\SyncNamespace\SyncNamespaceReadRepositoryCreateTrait;

/**
 * Class LotItemSyncDataLoader
 */
class LotItemSyncDataLoader extends CustomizableClass
{
    use LotItemAwareTrait;
    use SyncNamespaceReadRepositoryCreateTrait;
    use SystemAccountAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array - return values for Lot Item Syncs
     */
    public function load(): array
    {
        return $this->createSyncNamespaceReadRepository()
            ->enableReadOnlyDb(true)
            ->filterAccountId($this->getSystemAccountId())
            ->filterActive(true)
            ->joinLotItemSyncFilterLotItemId($this->getLotItemId())
            ->orderByName()
            ->select(['esync.id', 'esync.key', 'sn.name'])
            ->loadRows();
    }
}
