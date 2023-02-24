<?php
/**
 * Trait for Account Renderer
 *
 * SAM-5015: Unite sync tables data scheme
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 28, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntitySync\Load;

/**
 * Trait EntitySyncLoaderAwareTrait
 * @package Sam\EntitySync\Load
 */
trait EntitySyncLoaderAwareTrait
{
    /**
     * @var EntitySyncLoader|null
     */
    protected ?EntitySyncLoader $entitySyncLoader = null;

    /**
     * @return EntitySyncLoader
     */
    protected function getEntitySyncLoader(): EntitySyncLoader
    {
        if ($this->entitySyncLoader === null) {
            $this->entitySyncLoader = EntitySyncLoader::new();
        }
        return $this->entitySyncLoader;
    }

    /**
     * @param EntitySyncLoader $entitySyncLoader
     * @return static
     * @internal
     */
    public function setEntitySyncLoader(EntitySyncLoader $entitySyncLoader): static
    {
        $this->entitySyncLoader = $entitySyncLoader;
        return $this;
    }
}
