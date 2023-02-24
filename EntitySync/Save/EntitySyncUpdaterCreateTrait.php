<?php
/**
 * Trait for EntitySyncUpdater
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

/**
 * Trait EntitySyncEditorCreateTrait
 * @package Sam\EntitySync\Save
 */
trait EntitySyncUpdaterCreateTrait
{
    /**
     * @var EntitySyncUpdater|null
     */
    protected ?EntitySyncUpdater $entitySyncUpdater = null;

    /**
     * @return EntitySyncUpdater
     */
    protected function getEntitySyncUpdater(): EntitySyncUpdater
    {
        if ($this->entitySyncUpdater === null) {
            $this->entitySyncUpdater = EntitySyncUpdater::new();
        }
        return $this->entitySyncUpdater;
    }

    /**
     * @param EntitySyncUpdater $entitySyncUpdater
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setEntitySyncUpdater(EntitySyncUpdater $entitySyncUpdater): static
    {
        $this->entitySyncUpdater = $entitySyncUpdater;
        return $this;
    }
}
