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

namespace Sam\EntityMaker\Base\Save\Internal\EntitySync;

/**
 * Trait EntitySyncSavingIntegratorCreateTrait
 * @package Sam\EntityMaker\Base\Save\Internal\EntitySync
 */
trait EntitySyncSavingIntegratorCreateTrait
{
    /**
     * @var EntitySyncSavingIntegrator|null
     */
    protected ?EntitySyncSavingIntegrator $entitySyncSavingIntegrator = null;

    /**
     * @return EntitySyncSavingIntegrator
     */
    protected function createEntitySyncSavingIntegrator(): EntitySyncSavingIntegrator
    {
        return $this->entitySyncSavingIntegrator ?: EntitySyncSavingIntegrator::new();
    }

    /**
     * @param EntitySyncSavingIntegrator $entitySyncSavingIntegrator
     * @return $this
     * @internal
     */
    public function setEntitySyncSavingIntegrator(EntitySyncSavingIntegrator $entitySyncSavingIntegrator): static
    {
        $this->entitySyncSavingIntegrator = $entitySyncSavingIntegrator;
        return $this;
    }
}
