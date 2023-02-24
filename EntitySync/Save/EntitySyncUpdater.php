<?php
/**
 * Update EntitySync record
 *
 * SAM-4989: User Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-09-20
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntitySync\Save;

use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\EntitySync\Load\EntitySyncLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\EntitySync\EntitySyncWriteRepositoryAwareTrait;

class EntitySyncUpdater extends CustomizableClass
{
    use CurrentDateTrait;
    use EntitySyncLoaderAwareTrait;
    use EntitySyncWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $type
     * @param string $key
     * @param int|null $namespaceId
     * @param int $accountId
     * @param int $editorUserId
     */
    public function updateLastSyncIn(
        int $type,
        string $key,
        ?int $namespaceId,
        int $accountId,
        int $editorUserId
    ): void {
        if (!$namespaceId) {
            return;
        }
        $entitySync = $this->getEntitySyncLoader()->loadByType($type, $key, $namespaceId, $accountId);
        if ($entitySync) {
            $entitySync->LastSyncIn = $this->getCurrentDateUtc();
            $this->getEntitySyncWriteRepository()->saveWithModifier($entitySync, $editorUserId);
        }
    }
}
