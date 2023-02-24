<?php
/**
 * Sync Namespace List Data Loader
 *
 * SAM-6282: Refactor Sync Namespace List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 13, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SyncNamespaceListForm\Load;

use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\SyncNamespace\SyncNamespaceReadRepository;
use Sam\Storage\ReadRepository\Entity\SyncNamespace\SyncNamespaceReadRepositoryCreateTrait;
use Sam\View\Admin\Form\SyncNamespaceListForm\SyncNamespaceListConstants;

/**
 * Class SyncNamespaceListDataLoader
 */
class SyncNamespaceListDataLoader extends CustomizableClass
{
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;
    use SyncNamespaceReadRepositoryCreateTrait;
    use SystemAccountAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int - return value of Sync Namespaces count
     */
    public function count(): int
    {
        return $this->prepareSyncNamespaceRepository()->count();
    }

    /**
     * @return array - return values for Sync Namespaces
     */
    public function load(): array
    {
        $repo = $this->prepareSyncNamespaceRepository();

        if ($this->getSortColumn() === SyncNamespaceListConstants::ORD_NAME) {
            $repo->orderByName($this->isAscendingOrder());
        } else {
            $repo->orderById($this->isAscendingOrder());
        }

        if ($this->getOffset()) {
            $repo->offset($this->getOffset());
        }

        if ($this->getLimit()) {
            $repo->limit($this->getLimit());
        }

        return $repo->loadEntities();
    }

    /**
     * @return SyncNamespaceReadRepository
     */
    protected function prepareSyncNamespaceRepository(): SyncNamespaceReadRepository
    {
        return $this->createSyncNamespaceReadRepository()
            ->filterActive(true)
            ->filterAccountId($this->getSystemAccountId());
    }
}
