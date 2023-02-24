<?php
/**
 * SAM-4819: Entity aware traits
 *
 * Parent class for our entity aggregates.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\Aggregate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\ClearableInterface;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;

/**
 * Parent class for concrete entity aggregates
 * @package Sam\Storage\Entity\Aggregate
 */
abstract class EntityAggregateBase extends CustomizableClass implements ClearableInterface
{
    use ConfigRepositoryAwareTrait;
    use FormStateLongevityAwareTrait;

    /**
     * Enable/disable MemoryCachingManager on entity load
     * @var bool|null null to follow value of core->cache->memory->enabled
     */
    private ?bool $isMemoryCaching = null;

    /**
     * Each entity aggregate class should implement un-defining of its aggregated properties
     * @return static
     */
    abstract public function clear(): static;

    /**
     * @param bool $enable
     * @return static
     */
    public function enableMemoryCaching(bool $enable): static
    {
        $this->isMemoryCaching = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMemoryCaching(): bool
    {
        if ($this->isMemoryCaching === null) {
            $this->isMemoryCaching = $this->cfg()->get('core->cache->memory->enabled');
        }
        return $this->isMemoryCaching;
    }
}
