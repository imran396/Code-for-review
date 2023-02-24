<?php
/**
 * SAM-5015: Unite sync tables data scheme
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Save\Internal\EntitySync\Internal\Save;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\EntityMaker\Base\Save\Internal\EntitySync\Internal\Save\EntitySyncSavingInput as Input;
use Sam\EntityMaker\Base\Save\Internal\EntitySync\Internal\Save\EntitySyncSavingResult as Result;
use Sam\EntitySync\Load\EntitySyncLoaderAwareTrait;
use Sam\EntitySync\Load\Exception\CouldNotFindEntitySync;
use Sam\Storage\WriteRepository\Entity\EntitySync\EntitySyncWriteRepositoryAwareTrait;

/**
 * Class EntitySyncSaver
 * @package Sam\EntityMaker\Base\Save\EntitySync
 */
class EntitySyncSaver extends CustomizableClass
{
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use EntitySyncLoaderAwareTrait;
    use EntitySyncWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create new EntitySync record.
     * @param Input $input
     * @return Result
     */
    public function create(Input $input): Result
    {
        $result = Result::new()->construct();
        if (!$input->syncKey) {
            return $result->addInfo(Result::INFO_SYNC_KEY_NOT_DEFINED);
        }

        if (!$input->syncNamespaceId) {
            return $result->addInfo(Result::INFO_SYNC_NAMESPACE_NOT_DEFINED);
        }

        $entitySync = $this->createEntityFactory()->entitySync();
        $entitySync->EntityId = $input->entityId;
        $entitySync->EntityType = $input->entityType;
        $entitySync->Key = $input->syncKey;
        $entitySync->LastSyncIn = $input->lastSyncIn ?? $this->getCurrentDateUtc();
        $entitySync->SyncNamespaceId = $input->syncNamespaceId;
        $this->getEntitySyncWriteRepository()->saveWithModifier($entitySync, $input->editorUserId);
        $result->entitySync = $entitySync;
        return $result;
    }

    /**
     * Update existing EntitySync record.
     * @param Input $input
     * @return Result
     */
    public function update(Input $input): Result
    {
        $result = Result::new()->construct();
        if (!$input->syncKey) {
            return $result->addInfo(Result::INFO_SYNC_KEY_NOT_DEFINED);
        }

        if (!$input->syncNamespaceId) {
            return $result->addInfo(Result::INFO_SYNC_NAMESPACE_NOT_DEFINED);
        }

        $entitySync = $this->getEntitySyncLoader()->loadByTypeAndEntityId($input->entityType, $input->entityId);
        if (!$entitySync) {
            throw CouldNotFindEntitySync::withEntityIdAndType($input->entityId, $input->entityType);
        }

        $entitySync->Key = $input->syncKey;
        $entitySync->LastSyncIn = $input->lastSyncIn ?? $this->getCurrentDateUtc();
        $this->getEntitySyncWriteRepository()->saveWithModifier($entitySync, $input->editorUserId);
        $result->entitySync = $entitySync;
        return $result;
    }
}
