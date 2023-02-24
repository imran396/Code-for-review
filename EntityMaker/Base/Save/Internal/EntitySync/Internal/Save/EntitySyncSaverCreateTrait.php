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

/**
 * Trait EntitySyncSaverCreateTrait
 * @package Sam\EntityMaker\Base\Save\EntitySync
 */
trait EntitySyncSaverCreateTrait
{
    protected ?EntitySyncSaver $entitySyncSaver = null;

    /**
     * @return EntitySyncSaver
     */
    protected function createEntitySyncSaver(): EntitySyncSaver
    {
        return $this->entitySyncSaver ?: EntitySyncSaver::new();
    }

    /**
     * @param EntitySyncSaver $entitySyncSaver
     * @return $this
     * @internal
     */
    public function setEntitySyncSaver(EntitySyncSaver $entitySyncSaver): static
    {
        $this->entitySyncSaver = $entitySyncSaver;
        return $this;
    }
}
