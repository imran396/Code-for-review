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

use DateTime;
use Sam\Core\Service\CustomizableClass;

/**
 * Class EntitySyncSavingInput
 * @package Sam\EntityMaker\Base\Save\EntitySync
 */
class EntitySyncSavingInput extends CustomizableClass
{
    /**
     * @var string
     */
    public string $syncKey;
    /**
     * @var int|null
     */
    public ?int $syncNamespaceId;
    /**
     * @var int|null
     */
    public ?int $entityId;
    /**
     * @var int
     */
    public int $entityType;
    /**
     * @var int
     */
    public int $editorUserId;
    /**
     * @var DateTime|null
     */
    public ?DateTime $lastSyncIn;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $syncKey
     * @param int|null $syncNamespaceId
     * @param int|null $entityId
     * @param int $entityType
     * @param int $editorUserId
     * @param DateTime|null $lastSyncIn
     * @return $this
     */
    public function construct(
        string $syncKey,
        ?int $syncNamespaceId,
        ?int $entityId,
        int $entityType,
        int $editorUserId,
        ?DateTime $lastSyncIn = null
    ): static {
        $this->syncKey = $syncKey;
        $this->syncNamespaceId = $syncNamespaceId;
        $this->entityId = $entityId;
        $this->entityType = $entityType;
        $this->editorUserId = $editorUserId;
        $this->lastSyncIn = $lastSyncIn;
        return $this;
    }
}
