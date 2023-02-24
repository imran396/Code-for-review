<?php
/**
 * SAM-6611: Move entity cloning logic to customizable class
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 08, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\Copy;

/**
 * Trait EntityClonerCreateTrait
 * @package Sam\Storage\Entity\Copy
 */
trait EntityClonerCreateTrait
{
    protected ?EntityCloner $entityCloner = null;

    /**
     * @return EntityCloner
     */
    protected function createEntityCloner(): EntityCloner
    {
        return $this->entityCloner ?: EntityCloner::new();
    }

    /**
     * @param EntityCloner $entityCloner
     * @return $this
     * @internal
     */
    public function setEntityCloner(EntityCloner $entityCloner): static
    {
        $this->entityCloner = $entityCloner;
        return $this;
    }
}
